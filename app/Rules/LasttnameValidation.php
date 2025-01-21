<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class LasttnameValidation implements ValidationRule
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
                'required' => 'وارد کردن نام خانوادگی الزامی است.',
                'min' => 'نام خانوادگی حداقل باید شامل 2 کاراکتر باشد.',
                'max' => 'نام خانوادگی طولانی میباشد.',
                'regex' => 'نام خانوادگی وارد شده نامعتبر میباشد.'
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
