<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class UsertitleValidation implements ValidationRule
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
                    'min:3',
                    'max:255',
                    'regex:/^[\x{0600}-\x{06FF}\s]+$/u'
                ],
            ],
            [
                'required' => 'وارد نمودن عنوان پرسنل الزامیست.',
                'min' => 'عنوان پرسنل حداقل باید سه کاراکتر باشد.',
                'max' => 'عنوان پرسنل وارد شده بیش از حد مجاز میباشد.',
                'regex' => 'عنوان پرسنل فقط باید شامل حروف فارسی باشد',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
