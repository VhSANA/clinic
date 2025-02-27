<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PasswordValidation implements ValidationRule
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
            [ $attribute => [
                    Password::min(5)
                ]
            ],
            [
                'required' => 'وارد نمودن رمزعبور الزامیست.',
                'confirmed' => 'تکرار رمز عبور با خود رمزعبور مطابقت ندارد.',
                'min' => 'رمز عبور باید حداقل شامل 5 کاراکتر باشد.',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
