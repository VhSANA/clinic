<?php

use App\Models\User;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;
use Symfony\Component\CssSelector\XPath\Extension\FunctionExtension;

if (! function_exists('profileImageFunction')) {
    function profileImageFunction(User $user)
    {
        return ($user->personnel == null) ? 'https://t4.ftcdn.net/jpg/05/49/98/39/360_F_549983970_bRCkYfk0P6PP5fKbMhZMIb07mCJ6esXL.jpg' : $user->personnel->image_url;
    }
}


// shorten description
if (! function_exists('substrDescription')) {
    function substrDescription($model) {
        if (empty($model->description)) {
            return '-';
        } else {
            if (strlen($model->description) > 50) {
                return substr($model->description, 0, 50) . " ...";
            } else {
                return $model->description;
            }
        }
    }
}


// json response and redirect route return
if (! function_exists('redirectWithJson')) {
    function redirectWithJson(
        bool $status,
        string $message,
        array|string $data = [],
        int $code = 200,
        $route 
        ) {
        // json response
        response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);

        // redirect to
        return redirect(route($route));
    }
}


// select tag option values for relation_status and gender
if (! function_exists('optionDetails')) {
    function optionDetails($name, $model = null) {
        switch ($name) {
            case 'gender':
                echo '<option value="male" ' . (old('gender', $model->gender) == 'male' ? 'selected' : '') . '>مرد</option>';
                echo '<option value="female" ' . (old('gender', $model->gender) == 'female' ? 'selected' : '') . '>زن</option>';
                break;

            case 'relation_status':
                echo '<option value="single" ' . (old('relation_status', $model->relation_status) == 'single' ? 'selected' : '') . '>مجرد</option>';
                echo '<option value="married" ' . (old('relation_status', $model->relation_status) == 'married' ? 'selected' : '') . '>متاهل</option>';
                break;
        }
    }
}


// add - if value is null or empty
if (! function_exists('addDashifEmpty')) {
    function addDashifEmpty($model, $column) {
        if (empty($model->$column)) {
            return '-';
        }

        return $model->$column;
    }
}

// morilog jalali converter
if (! function_exists('convertToJalali')) {
    function convertToJalali($date) {
        return Jalalian::fromCarbon(Carbon::create($date))->format('%A, %d %B %Y');
    }
}

// convert days of calender to Jalalian date format
if (! function_exists('convertCalendarDayToPersianDate')) {
    function convertCalendarDayToPersianDate($currentDateAsJalalian, $day) {
        return $currentDateAsJalalian->format('Y-m') . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
    }
}

// eplode date into year, month and day and convert it to Carbon
if (! function_exists('convertExplodedDate')) {
    function convertExplodedDate($date) {
        list(
            $year,
            $month,
            $day
        ) = explode('-', $date);

        // convert to Carbon
        $carbonedDate = (new Jalalian($year, $month, $day))->toCarbon()->toDateTimeString();

        return $carbonedDate;
    }
}
