<?php

namespace App\Rules;

use App\Models\Schedule;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use RealRashid\SweetAlert\Facades\Alert;

class TimeLimitValidation implements ValidationRule
{
    protected $schedule;

    public function __construct($schedule) {
        $this->schedule = $schedule;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $schedule = Schedule::find($this->schedule);
            if (! ((Carbon::parse($schedule->from_date)->toTimeString() <= "$value:00") && (Carbon::parse($schedule->to_date)->toTimeString() >= "$value:00"))) {
                $fail('زمان وارد شده معتبر نیست');
                Alert::error('خطا!', 'زمان وارد شده معتبر نیست');
            }
        } catch (\Exception $e) {
            $fail('مقدار وارد شده معتبر نیست.');
        }
    }
}
