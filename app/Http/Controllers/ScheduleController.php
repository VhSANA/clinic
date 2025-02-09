<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\MedicalServices;
use App\Models\Personnel;
use App\Models\Room;
use App\Models\Schedule;
use App\Rules\FromTimeValidation;
use App\Rules\PersonnelValidation;
use App\Rules\ShiftTitleValidation;
use App\Rules\ToTimeValidation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    // get data from DB
        // personnel
        $personnels = Personnel::query();

        // search personnel
        if ($request->has('search')) {
            $keyword = $request['search'];
            $personnels->where('full_name', 'like', "%$keyword%")->orWhere('personnel_code', 'like', "%$keyword%");
        }
        $personnels = $personnels->latest()->paginate(5);

        // room
        $rooms = Room::all();

        // Determine the start and end dates of the current week. change value if we have gotodate name
        $currentDate = Carbon::now();
        if ($request->has('week')) {
            $currentDate = Carbon::parse($request['week']);
        } else if ($request->has('gotodate')) {
            $currentDate = Carbon::parse($request['gotodate']);
        } else if ($request->has('selectedWeek')) {
            $currentDate = Carbon::parse($request['selectedWeek']);
        }

        // define firstday as satureday and lastday of week as friday
        $startOfWeek = $currentDate->copy()->startOfWeek(6);
        $endOfWeek = $startOfWeek->copy()->addDays(6);

        // Retrieve calendar entries for the current week
        $calendars = Calendar::with('schedules')->whereBetween('date', [$startOfWeek, $endOfWeek])->get();

        // Extract schedules from the calendars
        $schedules = collect();
        foreach ($calendars as $calendar) {
            $schedules = $schedules->merge($calendar->schedules);
        }

        // Get the list of personnel IDs who have shifts in the current week
        $chosen_personnels = $schedules->pluck('personnel_id')->unique()->toArray();

        // If a personnel is selected via the request, add it to the chosen_personnels array
        if ($request['personnel_id']) {
            array_push($chosen_personnels, $request['personnel_id']);
        }

        // Convert dates to Jalalian
        $startOfWeekJalali = jdate($startOfWeek);
        $endOfWeekJalali = jdate($endOfWeek);

        return view('admin.schedule.all-schedule', [
            'schedules' => $schedules,
            'calendars' => $calendars,
            'startOfWeek' => $startOfWeekJalali,
            'endOfWeek' => $endOfWeekJalali,
            'currentDate' => $currentDate,
            'personnels' => $personnels,
            'chosen_personnels' => $chosen_personnels,
            'rooms' => $rooms,
            'selectedWeek' => $currentDate->toDateString()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function store(Request $request)
    {
        // get date of the day as Carbon
        $identifier = $request['schedule_date_id'] . '_' . $request['personnel_id'];
        $date = Calendar::find($request['schedule_date_id']);
        $selected_work_day = Carbon::parse(Carbon::parse($date->date))->toDateString();
        $selected_work_day_with_from_date = "$selected_work_day {$request['from_date_' . $identifier]}:00";
        $selected_work_day_with_to_date = "$selected_work_day {$request['to_date_' . $identifier]}:00";

        // validations
        $validator = Validator::make($request->all(), [
            "title_{$identifier}" => [new ShiftTitleValidation],
            "from_date_{$identifier}" => [new FromTimeValidation($selected_work_day_with_from_date)],
            "to_date_{$identifier}" => [new ToTimeValidation($selected_work_day_with_from_date)],
            "personnel_id" => ['required', new PersonnelValidation, 'exists:personnels,id'],
            "schedule_date_id" => ["required", "exists:schedule_dates,id"],
            "service_{$identifier}" => ["required", "exists:medical_services,id"],
            "room_{$identifier}" => ['required', 'exists:rooms,id'],
        ], [
            "service_{$identifier}.required" => 'انتخاب خدمت درمانی الزامیست.',
            "room_{$identifier}.required" => 'انتخاب اتاق الزامیست.',
        ]);

        if ($validator->fails()) {
            response()->json([
                'message' => 'validation errors'
            ], 422);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with("modal_open_{$identifier}", true);
        }

        // stop storing to DB if request time is occuring in past
        if (jdate($selected_work_day_with_from_date) < jdate(Carbon::now('Asia/Tehran'))) {
            Alert::error('عملیات غیرمجاز!', 'نمیتوان در تاریخ گذشته تغییراتی انجام داد.');
            return back();
        }

        // get capacity of room in selected day
        $room = Room::find($request['room_' . $identifier]);
        $calendar = Calendar::firstWhere('date', Carbon::parse($selected_work_day)->toDateTimeString());
        $room_ids = $calendar->schedules->pluck('room_id')->toArray();
        $room_count = array_count_values($room_ids)[$room->id] ?? 0;

        try {
            // check if room has capacity or not
            if ($room_count >= $room->personnel_capacity) {
                Alert::error('خطا!','ظرفیت اتاق تکمیل میباشد!');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'ظرفیت اتاق تکمیل میباشد!',
                        'status' => 'error'
                    ], 422);
                }

                return back();
            }

            // prevent from adding duplicated value
            $duplication_exist = Schedule::where('from_date', $selected_work_day_with_from_date)
                                ->where('to_date', $selected_work_day_with_to_date)
                                ->where('schedule_date_id', $request["schedule_date_id"])
                                ->where('personnel_id', $request['personnel_id'])
                                ->where('medical_service_id', MedicalServices::find($request["service_$identifier"])->id)
                                ->where('room_id', $request["room_{$identifier}"])
                                ->exists();

            if ($duplication_exist) {
                Alert::error('خطا!','مورد مشابه قبلا در سیستم ثبت شده است!');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'مورد مشابه قبلا در سیستم ثبت شده است!',
                        'status' => 'error'
                    ], 409);
                }

                return back();
            }

            // save to DB
            Schedule::create([
                'title' => $request["title_{$identifier}"],
                'from_date' => $selected_work_day_with_from_date,
                'to_date' => $selected_work_day_with_to_date,
                'schedule_date_id' => $request["schedule_date_id"],
                'room_id' => $request["room_{$identifier}"],
                'personnel_id' => $request['personnel_id'],
                'medical_service_id' => MedicalServices::find($request["service_$identifier"])->id,
                'is_appointable' => true,
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'شیفت کاری با موفقیت به تقویم اضافه شد.',
                    'status' => true
                ], 200);
            }

            // success alert
            Alert::success('عملیات موفقیت آمیز!', 'شیفت کاری با موفقیت افزوده شد.');

            return back();
        } catch (\Exception $e) {
            Alert::error('خطا!', 'مشکلی در افزودن شیفت کاری به وجود آمد.');

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'مشکلی در افزودن شیفت کاری به وجود آمد.',
                    'status' => 'error'
                ], 500);
            }

            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        // get date of day and other details
        $identifier = $schedule->id;
        $date = Calendar::find($request['schedule_date_id']);
        $selected_work_day = Carbon::parse(Carbon::parse($date->date))->toDateString();
        $selected_work_day_with_from_date = "$selected_work_day {$request['from_date_' . $identifier]}";
        $selected_work_day_with_to_date = "$selected_work_day {$request['to_date_' . $identifier]}";

        // validations
        $validator = Validator::make($request->all(), [
            "title_{$identifier}" => [new ShiftTitleValidation],
            "from_date_{$identifier}" => [new FromTimeValidation($selected_work_day_with_from_date)],
            "to_date_{$identifier}" => [new ToTimeValidation($selected_work_day_with_from_date)],
            "personnel_id" => ['required', new PersonnelValidation, 'exists:personnels,id'],
            "schedule_date_id" => ["required", "exists:schedule_dates,id"],
            "service_{$identifier}" => ["required", "exists:medical_services,id"],
            "room_{$identifier}" => ['required', 'exists:rooms,id'],
        ], [
            "service_{$identifier}.required" => 'انتخاب خدمت درمانی الزامیست.',
            "room_{$identifier}.required" => 'انتخاب اتاق الزامیست.',
        ]);

        // open modal if validation error exists
        if ($validator->fails()) {
            response()->json([
                'message' => 'validation errors'
            ], 422);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with("edit_modal_open_{$identifier}", true);
        }

        // if from_date is < now prevent user from editing
        if ((jdate($schedule->from_date) < jdate(Carbon::now('Asia/Tehran')))) {
            Alert::error('عملیات غیرمجاز!', 'نمیتوان تاریخ و زمان گذشته را ویرایش کرد.');
            return back();
        }

        // get capacity of room in selected day
        $room = Room::find($request['room_' . $identifier]);
        $calendar = Calendar::firstWhere('date', Carbon::parse($selected_work_day)->toDateTimeString());
        $room_ids = $calendar->schedules->pluck('room_id')->toArray();
        $room_count = array_count_values($room_ids)[$room->id] ?? 0;

        try {
            // check if selected room == room saved in DB => ignore personnel_capacity
            if (! $room->id == $schedule->room_id) {
                if ($room_count >= $room->personnel_capacity) {
                    Alert::error('خطا!','ظرفیت اتاق تکمیل میباشد!');

                    // JSON response
                    if (request()->expectsJson()) {
                        return response()->json([
                            'message' => 'ظرفیت اتاق تکمیل میباشد!',
                            'status' => 'error'
                        ], status: 422);
                    }

                    return back();
                }
            }

            // prevent from adding duplicated value
            $duplication_exist = Schedule::where('from_date', $selected_work_day_with_from_date)
                                ->where('to_date', $selected_work_day_with_to_date)
                                ->where('schedule_date_id', $request["schedule_date_id"])
                                ->where('personnel_id', $request['personnel_id'])
                                ->where('medical_service_id', MedicalServices::find($request["service_$identifier"])->id)
                                ->where('room_id', $request["room_{$identifier}"])
                                ->exists();

            if ($duplication_exist) {
                Alert::warning('ویرایشی رخ نداد','موردی مشابه قبلا در سیستم ثبت شده بود!');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'موردی مشابه قبلا در سیستم ثبت شده بود!',
                        'status' => 'warning'
                    ], 409);
                }

                return back();
            }

            // update data in DB
            $schedule->update([
                'title' => $request["title_{$identifier}"],
                'from_date' => $selected_work_day_with_from_date,
                'to_date' => $selected_work_day_with_to_date,
                'schedule_date_id' => $request["schedule_date_id"],
                'room_id' => $request["room_{$identifier}"],
                'personnel_id' => $request['personnel_id'],
                'medical_service_id' => MedicalServices::find($request["service_$identifier"])->id,
                'is_appointable' => true,
            ]);

            // response JSON
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'شیفت کاری با موفقیت ویرایش شد.',
                    'status' => true
                ], 200);
            }

            // success alert
            Alert::success('عملیات موفقیت آمیز!', 'شیفت کاری با موفقیت ویرایش شد.');

            return back();
        } catch (\Exception $E) {
            Alert::error('خطا!', 'مشکلی در ویرایش شیفت کاری به وجود آمد.');

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'مشکلی در ویرایش شیفت کاری به وجود آمد.',
                    'status' => 'error'
                ], 500);
            }

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        // stop delete if present time has passed date
        if ((jdate($schedule->from_date) < jdate(Carbon::now('Asia/Tehran')))) {
            Alert::error('عملیات غیرمجاز!', 'نمیتوان تاریخ و زمان گذشته را حذف کرد.');
            return back();
        }

        try {
            $schedule->deleteOrFail();

            // success alert
            Alert::success('عملیات موفقیت آمیز!', 'شیفت کاری با موفقیت حذف شد.');

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'شیفت کاری با موفقیت حذف شد.',
                    'status' => 'success'
                ], 200);
            }

            return redirect()->back();
        } catch (\Exception $e) {
            // error alert
            Alert::error('خطا!', 'مشکلی در حذف شیفت کاری به وجود آمد.');

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'مشکلی در حذف شیفت کاری به وجود آمد.',
                    'status' => 'error'
                ], 500);
            }

            return redirect()->back();
        }
    }

    /**
     * Copy the specified resource from schedule.
     */
    public function copy(Request $request, Schedule $schedule)
    {
        try {
            // Store the copied schedule data in the session
            session()->put('copied_schedule', [
                'title' => $schedule->title,
                'from_date' => $schedule->from_date,
                'to_date' => $schedule->to_date,
                'personnel_id' => $schedule->personnel_id,
                'room_id' => $schedule->room_id,
                'medical_service_id' => $schedule->medical_service_id,
            ]);

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'شیفت کاری با موفقیت کپی شد!',
                    'status' => 'success'
                ], 200);
            }

            Alert::toast('شیفت کاری با موفقیت کپی شد!');

            return back();
        } catch (\Exception $e) {
            Alert::toast('خطایی رخ داده است.');

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'خطایی رخ داده است.',
                    'status' => 'error'
                ], 422);
            }

            return back();
        }
    }
    /**
     * paste the specified resource from schedule.
     */
    public function paste(Request $request, Personnel $personnel)
    {
        try {
            // Retrieve the copied schedule data from the session
            $copiedSchedule = session()->get('copied_schedule');

            // return back user if there is nothing to paste
            if (!$copiedSchedule) {
                Alert::toast('شیفتی برای جاگذاری وجود ندارد!');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'شیفتی برای جاگذاری وجود ندارد!',
                        'status' => 'error'
                    ], 422);
                }

                return back();
            }

            // check if copied shift's personnel == the personnel we want to paste new shift to it
            if ($personnel->id != $copiedSchedule['personnel_id']) {
                Alert::error("عملیات غیر مجاز!", "شیفت مورد نظر فقط برای " . Personnel::find($copiedSchedule['personnel_id'])->full_name . ' قابلیت کپی دارد');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'کاربر اشتباه انتخاب شده',
                        'status' => 'error'
                    ], 422);
                }

                return back();
            }

            // get capacity of room in selected day
            $room = Room::find($copiedSchedule['room_id']);
            $calendar = Calendar::find($request['date']);
            $room_ids = $calendar->schedules->pluck('room_id')->toArray();
            $room_count = array_count_values($room_ids)[$room->id] ?? 0;

            // stop storing to DB if request time is occuring in past
            if (jdate(Carbon::parse($calendar->date)->toDatestring() . ' ' . jdate($copiedSchedule['from_date'])->toTimeString())->toDateTimeString() < jdate(Carbon::now('Asia/Tehran'))->toDateTimeString()) {
                Alert::error('عملیات غیرمجاز!', '.ساعات شیفت کپی شده از زمان حال گذشته است');
                return back();
            }

            // check if room has capacity or not
            if ($room_count >= $room->personnel_capacity) {
                Alert::error('خطا!','ظرفیت اتاق تکمیل میباشد!');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'ظرفیت اتاق تکمیل میباشد!',
                        'status' => 'error'
                    ], 422);
                }

                return back();
            }

            // prevent from adding duplicated value
            $duplication_exist = Schedule::where('from_date', Carbon::parse($calendar->date)->toDatestring() . ' ' . jdate($copiedSchedule['from_date'])->toTimeString())
                                ->where('to_date', Carbon::parse($calendar->date)->toDatestring() . ' ' . jdate($copiedSchedule['to_date'])->toTimeString())
                                ->where('schedule_date_id', $calendar->id)
                                ->where('personnel_id', $personnel->id)
                                ->where('medical_service_id', $copiedSchedule['medical_service_id'])
                                ->where('room_id', $copiedSchedule['room_id'])
                                ->exists();

            if ($duplication_exist) {
                Alert::error('خطا!','مورد مشابه قبلا در سیستم ثبت شده است!');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'مورد مشابه قبلا در سیستم ثبت شده است!',
                        'status' => 'error'
                    ], 409);
                }

                return back();
            }

            // save to DB
            Schedule::create([
                'title' => $copiedSchedule['title'],
                'from_date' => Carbon::parse($calendar->date)->toDatestring() . ' ' . jdate($copiedSchedule['from_date'])->toTimeString(),
                'to_date' => Carbon::parse($calendar->date)->toDatestring() . ' ' . jdate($copiedSchedule['to_date'])->toTimeString(),
                'schedule_date_id' => $calendar->id,
                'room_id' => $copiedSchedule['room_id'],
                'personnel_id' => $personnel->id,
                'medical_service_id' => $copiedSchedule['medical_service_id'],
                'is_appointable' => true,
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'شیفت کاری با موفقیت به تقویم اضافه شد.',
                    'status' => true
                ], 200);
            }

            // success alert
            Alert::success('عملیات موفقیت آمیز!', 'شیفت کاری با موفقیت افزوده شد.');

            return back();
        } catch (\Exception $e) {
            Alert::toast('خطایی رخ داده است.');

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'خطایی رخ داده است.',
                    'status' => 'error'
                ], 500);
            }

            return back();
        }
    }
}
