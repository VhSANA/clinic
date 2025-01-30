<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FromTimeValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $from_date = Carbon::parse($value)->toTimeString();
            $now = Carbon::now('Asia/Tehran')->toTimeString();

            if ($from_date < $now) {
                $fail('مقدار زمان آغاز شیفت نمیتواند کمتر از زمان حال باشد');
            }
        } catch (\Exception $e) {
            $fail('مقدار وارد شده معتبر نیست.');
        }
    }
}
