<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class ImageValidation implements ValidationRule
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
                    'nullable',
                    'image',
                    'mimes:jpeg,png,jpg',
                    'max:2048'],
            ],
            [
                'image' => 'فقط تصویر آپلود کنید.',
                'mimes' => 'فرمت فایل فقط باید بصورت jpg, png و jpeg باشد.',
                'max' => 'حجم تصویر نمیتواند بیشتر از 2 مگابایت باشد.',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
