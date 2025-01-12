<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class NationalcodeValidation implements ValidationRule
{
    public function __construct(public User $user) {}
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
                    'size:10',
                    Rule::unique('users', 'national_code')->ignore($this->user->id)
                ],
            ],
            [
                'required' => 'وارد نمودن کد ملی الزامیست.',
                'size' => 'کد ملی فقط باید شامل 10 رقم باشد.',
                'unique' => 'کد ملی قبلا ثبت شده است.',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
