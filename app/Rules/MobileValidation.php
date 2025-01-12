<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class MobileValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validator = Validator::make(
            [$attribute => $value],
            [
                $attribute => [
                    'required',
                    'regex:/^(?:0|98|\+98|\+980|0098|098|00980)?(9\d{9})$/'
                ],
            ],
            [
                'required' => 'وارد نمودن شماره موبایل الزامیست.',
                'regex' => 'شماره موبایل وارد شده معتبر نیست.',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
