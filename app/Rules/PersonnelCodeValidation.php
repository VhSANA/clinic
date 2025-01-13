<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PersonnelCodeValidation implements ValidationRule
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
                    'min:3',
                    Rule::unique('personnels', 'personnel_code')->ignore($this->user->id, 'user_id')
                ],
            ],
            [
                'required' => 'انتخاب کاربر الزامیست.',
                'min' => 'کد پرسنلی باید سه رقمی باشد.',
                'unique' => 'کد پرسنلی وارد شده قبلا ثبت شده است.',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
