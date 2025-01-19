<?php

namespace App\Rules;

use App\Models\Personnel;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PersonnelServiceValidation implements ValidationRule
{
    // public function __construct(public Personnel $personnel) {}
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
                    Rule::exists('personnels', 'id'),
                ],
            ],
            [
                'required' => 'انتخاب پرسنل الزامیست.',
                'exists' => 'پرسنلی یافت نشد.',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
