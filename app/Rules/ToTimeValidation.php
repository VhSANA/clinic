<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ToTimeValidation implements ValidationRule
{
    protected $from_date;

    public function __construct($from_date)
    {
        $this->from_date = $from_date;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $from_date = $this->from_date;
            $to_date = Carbon::parse($from_date)->toDateString() . ' ' . Carbon::parse($value)->toTimeString();
            $now = Carbon::now('Asia/Tehran')->toDateTimeString();

            if ($to_date < $now) {
                $fail('مقدار زمان وارد شده نمیتواند کمتر از زمان حال باشد');
            }

            if ($to_date < $from_date) {
                $fail('مقدار پایان شیفت نمیتواند کمتر از آغاز شیفت باشد');
            }
        } catch (\Exception $e) {
            $fail('مقدار وارد شده معتبر نیست.');
        }
    }
}
