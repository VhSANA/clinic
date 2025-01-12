<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsernameValidation implements ValidationRule
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
                    'string',
                    'min:5',
                    'max:20',
                    Rule::unique('users', 'username')->ignore($this->user->id)
                ],
            ],
            [
                'required' => 'وارد کردن نام کاربری الزامی است.',
                'min' => 'نام کاربری حداقل باید شامل 5 کاراکتر باشد.',
                'max' => 'نام کاربری نمیتواند بیشتر از 20 کاراکتر باشد.',
                'unique' => 'این نام کاربری قبلا ثبت شده است.',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
