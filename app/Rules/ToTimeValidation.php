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
            $selected_date = Carbon::parse($from_date)->toDateString() . ' ' . $value . ':00';
            $now = Carbon::now()->toDateTimeString();

            if ($selected_date < $now) {
                $fail('مقدار زمان وارد شده نمیتواند کمتر از زمان حال باشد');
            }

            if ($selected_date < $from_date) {
                $fail('مقدار پایان شیفت نمیتواند کمتر از آغاز شیفت باشد');
            }
        } catch (\Exception $e) {
            $fail('مقدار وارد شده معتبر نیست.');
        }
    }
}
