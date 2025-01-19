<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class EstimatedTimeValidation implements ValidationRule
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
                    'max:5',
                    'regex:/^[1-9]\d*$/',
                ],
            ],
            [
                'required' => 'وارد نمودن مدت زمان تقریبی الزامیست.',
                'max' => 'تعداد کاراکتر بیش از حد مجاز میباشد.',
                'regex' => 'مقدار اولیه نمیتواند صفر باشد',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
