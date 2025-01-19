<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class CapacityValidation implements ValidationRule
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
                    'min:1',
                    'regex:/^[1-9]\d*$/',
                ],
            ],
            [
                'required' => 'وارد کردن ظرفیت افراد اتاق الزامی است.',
                'min' => 'حداقل باید شامل یک کاراکتر باشد.',
                'regex' => 'ظرفیت افراد اتاق نمیتواند صفر باشد.'
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
