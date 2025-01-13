<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class FullnameValidation implements ValidationRule
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
                    'string',
                    'regex:/^[\x{0600}-\x{06FF}]+[\s]+[\x{0600}-\x{06FF}]+$/u',
                    'min:2',
                    'max:255'
                ],
            ],
            [
                'required' => 'وارد کردن نام و نام خانوداگی الزامی است.',
                'min' => 'نام و نام خانوداگی حداقل باید شامل 5 کاراکتر باشد.',
                'regex' => 'نام کامل باید شامل نام و نام خانوادگی باشد و فقط با حروف فارسی نوشته شده باشد.',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
