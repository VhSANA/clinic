<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use RealRashid\SweetAlert\Facades\Alert;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // get queries from DB
        $calendars = Calendar::query()->get();

        return view('admin.calender.all-calender', [
            'calendars' => $calendars,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // prevent if user tries to add empty value
        if ($request['add_work_date'] == '') {
            Alert::error('خطا!', 'تقویم نمیتواند خالی باشد.');

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'تقویم کاری خالی میباشد.',
                    'status' => 'error'
                ], 500);
            }

            return back();
        }

        // prevent if user tries to add work day in past
        if ($request['add_work_date'] < now('+03:30')) {
            Alert::error('خطا!', 'نمیتوان در تاریخ های گذشته روزکاری افزود.');

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'افزودن روزکاری در گذشته',
                    'status' => 'error'
                ], 500);
            }

            return back();
        }

        // save to DB
        Calendar::create([
            'is_holiday' => false,
            'date' => "{$request['add_work_date']} 00:00:00"
        ]);

        Alert::success('موفق!', 'روزکاری با موفقیت به تقویم افزوده شد.');

        // JSON response
        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'روزکاری افزوده شد',
                'status' => 'success'
            ], 200);
        }

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calendar $calendar)
    {
        foreach ($calendar->schedules as $schedule) {
            if (! $schedule->appointments->isEmpty()) {
                Alert::error('خطا!', 'روزکاری انتخاب شده را به دلیل وجود نوبت نمیتوان تعطیل کرد.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'عملیات غیر مجاز.',
                        'status' => 'error'
                    ], 500);
                }

                return back();
            }
        }

        if ($calendar->date < now('+03:30')) {
            Alert::error('خطا!', 'روزکاری منقضی شده را نمیتوان تعطیل کرد.');

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'عملیات غیر مجاز.',
                    'status' => 'error'
                ], 500);
            }

            return back();
        }
        $calendar->update([
            'is_holiday' => ! $calendar->is_holiday
        ]);

        $work_day_status = $calendar->is_holiday ? 'تعطیل' : 'فعال';

        Alert::success('موفق!', "روز کاری با موفقیت $work_day_status شد");

        // JSON response
        if (request()->expectsJson()) {
            return response()->json([
                'message' => "روز کاری با موفقیت $work_day_status شد",
                'status' => 'success'
            ], 200);
        }

        return back();
    }

    /**j
     * Remove the specified resource from storage.
     */
    public function destroy(Calendar $calendar)
    {
        // prevent deleting date if there is patient reservation exists
        foreach ($calendar->schedules as $schedule) {
            if (! $schedule->appointments->isEmpty()) {
                Alert::error('خطا!', 'روزکاری انتخاب شده را به دلیل وجود نوبت نمیتوان حذف کرد.');

                // JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'message' => 'عملیات غیر مجاز.',
                        'status' => 'error'
                    ], 500);
                }

                return back();
            }
        }

        // prevent from deleteing if calendars's date is < now
        if ($calendar->date < now('+03:30')) {
            Alert::error('خطا!', 'روزکاری منقضی شده را نمیتوان حذف کرد.');

            // JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'عملیات غیر مجاز.',
                    'status' => 'error'
                ], 500);
            }

            return back();
        }

        $calendar->deleteOrFail();

        Alert::success('موفق!', 'روز کاری با موفقیت حذف شد.');

        // JSON response
        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'روز کاری با موفقیت حذف شد.',
                'status' => 'success'
            ], 200);
        }

        return back();
    }
}
