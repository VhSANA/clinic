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

        // find personnel if selected and set to session
        // $chosen_personnels = [];
        // if ($request->has('personnel_id')) {
        //     // find personnel
        //     // return $request->all();
        //     // $chosen_personnels = explode(',', implode(',', $request->input('personnel_id')));
        // }

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
    public function store(Request $request, Schedule $schedule)
    {
        // get date of day which shift is being added to and pasre it to Carbon
        $identifier = $request['schedule_date_id'];
        $date = Calendar::find($identifier);
        $selected_work_day = Carbon::parse(Carbon::parse($date->date)->toDateString());
        $selected_work_day_with_from_date = $selected_work_day->toDateString() . ' ' . $request['from_date_'.$identifier] . ':00';
        $selected_work_day_with_to_date = $selected_work_day->toDateString() . ' ' . $request['to_date_'.$identifier] . ':00';


    // first check if work day is lewer or equal to now(), prevent from further actions
        // check if work day is < now or not
        if (jdate($selected_work_day_with_from_date) < jdate(Carbon::now('Asia/Tehran'))) {
            Alert::error('عملیات غیرمجاز!', 'نمیتوان در تاریخ گذشته تغییراتی انجام داد.');
            return back();
        }

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

    // if work day is > now()
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

        response()->json([
            'message' => 'شیفت کاری با موفقیت به تقویم اضافه شد.'
        ], 200);

        // success alert
        Alert::success('عملیات موفقیت آمیز!', 'شیفت کاری با موفقیت افزوده شد.');

        return back();
        // return redirect(route('schedule.index'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        return $request->all();
        // get date of day which shift is being added to and pasre it to Carbon
        $identifier = $request['schedule_date_id'];
        $date = Calendar::find($identifier);
        $selected_work_day = Carbon::parse(Carbon::parse($date->date)->toDateString());
        $selected_work_day_with_from_date = $selected_work_day->toDateString() . ' ' . $request['from_date_'.$identifier] . ':00';
        $selected_work_day_with_to_date = $selected_work_day->toDateString() . ' ' . $request['to_date_'.$identifier] . ':00';


    // first check if work day is lewer or equal to now(), prevent from further actions
        // check if work day is < now or not
        if (jdate($selected_work_day_with_from_date) < jdate(Carbon::now('Asia/Tehran'))) {
            Alert::error('عملیات غیرمجاز!', 'نمیتوان در تاریخ گذشته تغییراتی انجام داد.');
            return back();
        }

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
                ->with("edit_modal_open_{$identifier}", true);
        }

    // if work day is > now()
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

        response()->json([
            'message' => 'شیفت کاری با موفقیت به تقویم اضافه شد.'
        ], 200);

        // success alert
        Alert::success('عملیات موفقیت آمیز!', 'شیفت کاری با موفقیت افزوده شد.');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}
