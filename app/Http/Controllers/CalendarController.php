<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // TODO add validation if admin tries to delete the day as work day, bans admin from that action if there was appointments added to that day
        // get queries from DB
        $calendars = Calendar::query()->get();

    // // create Persian calender view
        // Check if a specific date is provided
        if ($request->has('gotodate')) {
            $goToDate = Jalalian::forge($request->input('gotodate'));
        } else {
            $goToDate = Jalalian::now();
        }

        // Create Persian calendar view
        $currentDate = $goToDate;

        // Retrieve and validate year and month
        $year = $request->input('year', $currentDate->getYear());
        $month = $request->input('month', $currentDate->getMonth());

        $currentDate = new Jalalian($year, $month, 1);

        $daysInMonth = $currentDate->getMonthDays();
        $firstDayOfMonth = $currentDate->getDayOfWeek();

        $monthName = $currentDate->format('%B %Y');

        // Calculate the last days of the previous month
        $previousMonth = $currentDate->subMonths(1);
        $daysInPreviousMonth = $previousMonth->getMonthDays();
        $lastDaysOfPreviousMonth = $daysInPreviousMonth - $firstDayOfMonth;

        // Calculate the first days of the next month
        $nextMonth = $currentDate->addMonths(1);
        $remainingDays = (7 - (($daysInMonth + $firstDayOfMonth) % 7)) % 7;

        return view('admin.calender.all-calender', [
            'calendars' => $calendars,
            'currentDate' => $currentDate,
            'daysInMonth' => $daysInMonth,
            'firstDayOfMonth' => $firstDayOfMonth,
            'monthName' => $monthName,
            'lastDaysOfPreviousMonth' => $lastDaysOfPreviousMonth,
            'daysInPreviousMonth' => $daysInPreviousMonth,
            'remainingDays' => $remainingDays,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request['add_work_date'] == '') {
            return redirectWithJson(false, 'تقویم کاری خالی میباشد.', [], 400, 'calendar.index');
        }

        // get Solar Hijri from input and seprate into year, month and day and convert it to Carbon
        $date = convertExplodedDate($request['add_work_date']);

        // save to DB
        Calendar::create([
            'is_holiday' => false,
            'date' => $date
        ]);

        // json respone and redirect
        response()->json([
            'message' => 'روز کاری با موفقیت اضافه شد'
        ]);
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calendar $calendar)
    {
        $calendar->update([
            'is_holiday' => ! $calendar->is_holiday
        ]);

        // json respone and redirect
        response()->json([
            'message' => 'روز کاری با موفقیت ویرایش شد'
        ]);
        return back();
    }

    /**j
     * Remove the specified resource from storage.
     */
    public function destroy(Calendar $calendar)
    {
        // TODO add validation if admin tries to delete the day as work day, bans admin from that action if there was appointments added to that day
        // TODO add for all methods if is happening in past show alert
        $calendar->deleteOrFail();

        // json respone and redirect
        response()->json([
            'message' => 'روز کاری با موفقیت حذف شد'
        ]);
        return back();
    }
}
