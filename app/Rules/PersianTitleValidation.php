<?php

namespace App\Rules;

use App\Models\Rule as ModelsRule;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PersianTitleValidation implements ValidationRule
{
    public function __construct(public ModelsRule $rule) {}
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
                    'min:2',
                    'max:250',
                    'regex:/^[\x{0600}-\x{06FF}0-9\s\p{P}]+$/u',
                    Rule::unique('rules', 'persian_title')->ignore($this->rule->id)
                ],
            ],
            [
                'required' => 'وارد کردن عنوان فارسی مقام الزامی است.',
                'string' => 'مقام باید فقط بصورت متن باشد.',
                'min' => 'حداقل باید شامل دو کاراکتر باشد.',
                'max' => 'مقدار عنوان طولانی میباشد.',
                'regex' => 'عنوان مقام باید فقط شامل حروف فارسی باشد.',
                'unique' => 'این عنوان قبلا ثبت شده است.'
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
