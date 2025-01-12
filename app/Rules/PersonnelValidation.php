<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PersonnelValidation implements ValidationRule
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
                    Rule::exists('users', 'id')
                ],
            ],
            [
                'required' => 'انتخاب کاربر الزامیست.',
                'exists' => 'کاربری یافت نشد.',

            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
