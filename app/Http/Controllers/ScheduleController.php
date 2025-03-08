<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\MedicalServices;
use App\Models\Personnel;
use App\Models\Room;
use App\Models\Rule;
use App\Models\Schedule;
use App\Rules\FromTimeValidation;
use App\Rules\PersonnelValidation;
use App\Rules\ShiftTitleValidation;
use App\Rules\ToTimeValidation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = Schedule::with('room', 'personnel', 'service', 'appointments', 'calendar', 'personnel.medicalservices')->get();
        $calendars = Calendar::with('schedules', 'schedules.room', 'schedules.personnel', 'schedules.service', 'schedules.appointments')->get();
        $rooms = Room::all();
        $rules = Rule::all();
        $personnels = Personnel::with('user.rules', 'medicalservices')->get();


// foreach ($personnels as $personnel) {
//     foreach ($personnel->medicalservices as $service) {
//         dd($service->pivot);

//     }
// }
        return view('admin.schedule.all-schedule', [
            'calendars' => $calendars,
            'schedules' => $schedules,
            'personnels' => $personnels,
            'rooms' => $rooms,
            'rules' => $rules,
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
        $personnel = Personnel::find($request['personnel_id']);
        $selected_work_day = Carbon::parse($date->date)->toDateString();
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
                ->with("add_schedule_modal", $request['schedule_date_id'] . '-' . $request['personnel_id'])
                ->with('selected_personnel', $request['personnel_id']);
        }

        // check selected service is related to selected personnel
        if (! $personnel->medicalservices->contains('id', $request['service_' . $identifier])) {
            Alert::error('عملیات غیرمجاز!', 'مغایرت میان پرسنل و خدمت انتخاب شده.');

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'مغایرت میان پرسنل و خدمت درمانی',
                    'status' => 'error'
                ], 422);
            }

            return back();
        }

        // stop storing to DB if request time is occuring in past
        if ($selected_work_day_with_from_date < now('+03:30')->toDateTimeString()) {
            Alert::error('عملیات غیرمجاز!', 'نمیتوان در تاریخ گذشته شیفتی افزود.');

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'عملیات غیر مجاز (تغییرات در تاریخ گذشته)',
                    'status' => 'error'
                ], 422);
            }

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
                Alert::error('خطا!','موردی مشابه قبلا در سیستم ثبت شده است!');

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
        $selected_work_day = Carbon::parse($date->date)->toDateString();
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
                ->with("edit_schedule_modal", $identifier);
        }

        // get capacity of room in selected day
        $room = Room::find($request['room_' . $identifier]);
        $calendar = Calendar::firstWhere('date', Carbon::parse($selected_work_day)->toDateTimeString());
        $room_ids = $calendar->schedules->pluck('room_id')->toArray();
        $room_count = array_count_values($room_ids)[$room->id] ?? 0;

        try {
            if (! $schedule->personnel->medicalservices->contains('id', $request['service_' . $identifier])) {
                Alert::error('عملیات غیرمجاز!', 'مغایرت میان پرسنل و خدمت انتخاب شده.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'مغایرت میان پرسنل و خدمت درمانی',
                        'status' => 'error'
                    ], 422);
                }

                return back();
            }

            // if from_date is < now prevent user from editing
            if ((jdate($schedule->from_date) < jdate(Carbon::now('Asia/Tehran')))) {
                Alert::error('عملیات غیرمجاز!', 'نمیتوان تاریخ و زمان گذشته را ویرایش کرد.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'ویرایش شیفت در گذشته',
                        'status' => 'error'
                    ], status: 422);
                }

                return back();
            }

            // prevent deleting schedule if there is appointments
            if (! $schedule->appointments->isEmpty()) {
                Alert::error('عملیات غیرمجاز!', 'به دلیل وجود نوبت، نمیتوان تاریخ مورد نظر را ویرایش کرد.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'حذف شیفت نوبت دار',
                        'status' => 'error'
                    ], status: 422);
                }

                return back();
            }

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
        } catch (\Throwable $e) {
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
        try {
            // prevent deleting schedule if there is appointments
            if (! $schedule->appointments->isEmpty()) {
                Alert::error('عملیات غیرمجاز!', 'به دلیل وجود نوبت، نمیتوان تاریخ مورد نظر را حذف کرد.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'حذف شیفت نوبت دار',
                        'status' => 'error'
                    ], status: 422);
                }

                return back();
            }

            // stop delete if present time has passed date
            if ($schedule->from_date < now('+03:30')) {
                Alert::error('عملیات غیرمجاز!', 'نمیتوان تاریخ و زمان گذشته را حذف کرد.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'حذف شیفت در گذشته',
                        'status' => 'error'
                    ], status: 422);
                }

                return back();
            }

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

            return back();
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

            return back();
        }
    }

    /**
     * paste the specified resource from schedule.
     */
    public function paste(Request $request)
    {
        try {
            // return back user if there is nothing to paste
            if (empty($request->all())) {


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
            $personnel = Personnel::find($request['personnel_id']);
            if (!$personnel) {


                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'کاربر انتخاب شده وجود ندارد',
                        'status' => 'error'
                    ], 422);
                }

                return back();
            }

            if ($request['check_personnel'] != $request['personnel_id']) {


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
            $room = Room::find($request['room_id']);
            $calendar = Calendar::find($request['schedule_date_id']);
            $room_ids = $calendar->schedules->pluck('room_id')->toArray();
            $room_count = array_count_values($room_ids)[$room->id] ?? 0;

            // stop storing to DB if request time is occuring in past
            if (Carbon::parse($calendar->date)->toDatestring() . ' ' . Carbon::parse($request['from_date'])->toTimeString() < Carbon::now('Asia/Tehran')->toDateTimeString()) {


                if (request()->expectsJson()) {
                    return response()->json([
                        'data' => $request->all(),
                        'message' => 'عملیات غیر مجاز',
                        'status' => 'error'
                    ], 422);
                }

                return back();
            }

            // check if room has capacity or not
            if ($room_count >= $room->personnel_capacity) {


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
            $duplication_exist = Schedule::where('from_date', Carbon::parse($calendar->date)->toDatestring() . ' ' . jdate($request['from_date'])->toTimeString())
                                ->where('to_date', Carbon::parse($calendar->date)->toDatestring() . ' ' . jdate($request['to_date'])->toTimeString())
                                ->where('schedule_date_id', $calendar->id)
                                ->where('personnel_id', $personnel->id)
                                ->where('medical_service_id', $request['service_id'])
                                ->where('room_id', $request['room_id'])
                                ->exists();

            if ($duplication_exist) {


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
                'title' => $request['title'],
                'from_date' => Carbon::parse($calendar->date)->toDatestring() . ' ' . jdate($request['from_date'])->toTimeString(),
                'to_date' => Carbon::parse($calendar->date)->toDatestring() . ' ' . jdate($request['to_date'])->toTimeString(),
                'schedule_date_id' => $calendar->id,
                'room_id' => $request['room_id'],
                'personnel_id' => $request['personnel_id'],
                'medical_service_id' => $request['service_id'],
                'is_appointable' => true,
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'شیفت کاری با موفقیت به تقویم اضافه شد.',
                    'status' => true
                ], 200);
            }

            // success alert


            return back();
        } catch (\Throwable $e) {
            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'خطایی رخ داده است.',
                    'status' => 'error',
                ], 500);
            }

            return back();
        }
    }
}
