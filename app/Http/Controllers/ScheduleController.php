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
        // schedule
        $schedules = Schedule::all();

        // personnel
        $personnels = Personnel::query()->paginate(perPage: 3);

        $chosen_personnels = [];
        if (! $schedules->isEmpty()) {
            foreach ($schedules as $schedule) {
                array_push($chosen_personnels, $schedule->personnel_id);
            }
        }

        if ($request['personnel_id']) {
            array_push($chosen_personnels,  $request['personnel_id']);
        }

        // room
        $rooms = Room::all();

        // Determine the start and end dates of the current week. change value if we have gotodate name
        $currentDate = Carbon::now();
        if ($request->has('week')) {
            $currentDate = Carbon::parse($request['week']);
        } else if ($request->has('gotodate')) {
            $currentDate = Carbon::parse($request['gotodate']);
        }

        // define firstday as satureday and lastday of week as friday
        $startOfWeek = $currentDate->copy()->startOfWeek(6);
        $endOfWeek = $startOfWeek->copy()->addDays(6);

        // Retrieve calendar entries for the current week
        $calendars = Calendar::whereBetween('date', [$startOfWeek, $endOfWeek])->get();

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
            // 'chosen_personnel' => $chosen_personnel,
            'rooms' => $rooms,
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
                        ], 422);
                    }

                    return back();
                }
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
}
