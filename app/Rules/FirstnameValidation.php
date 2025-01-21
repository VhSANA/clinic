<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class FirstnameValidation implements ValidationRule
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
                    'regex:/^[\x{0600}-\x{06FF}a-zA-Z\s]+$/u',
                    'min:2',
                    'max:255'
                ],
            ],
            [
                'required' => 'وارد کردن نام الزامی است.',
                'min' => 'نام حداقل باید شامل 2 کاراکتر باشد.',
                'max' => 'نام طولانی میباشد.',
                'regex' => 'نام وارد شده نامعتبر میباشد.',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
