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
        // create Persian calender view
        $currentDate = Jalalian::now();

        $year = $request->input('year', $currentDate->getYear());
        $month = $request->input('month', $currentDate->getMonth());

        $currentDate = new Jalalian($year, $month, 1);

        $daysInMonth = $currentDate->getMonthDays();
        $firstDayOfMonth = $currentDate->getDayOfWeek();

        $monthName = $currentDate->format('%B %Y');

        // Calculate the last days of the previous month
        $previousMonth = $currentDate->subMonths(1);
        $daysInPreviousMonth = $previousMonth->getMonthDays();
        $lastDaysOfPreviousMonth = $daysInPreviousMonth - $firstDayOfMonth + 1;

        // Calculate the first days of the next month
        $nextMonth = $currentDate->addMonths(1);
        $remainingDays = (7 - (($daysInMonth + $firstDayOfMonth) % 7)) % 7;

        return view('admin.calender.all-calender', [
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

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calendar $calendar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calendar $calendar)
    {

    }
}
