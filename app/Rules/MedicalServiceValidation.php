<?php

namespace App\Rules;

use App\Models\MedicalServices;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MedicalServiceValidation implements ValidationRule
{
    public function __construct(public MedicalServices $service) {}
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
                    Rule::unique('medical_services', 'name')->ignore($this->service->id)
                ],
            ],
            [
                'required' => 'وارد کردن عنوان خدمات درمانی الزامی است.',
                'min' => ' حداقل باید 2 کاراکتر باشد.',
                'max' => 'عنوان بیش از حد طولانی میباشد',
                'unique' => 'این خدمات درمانی قبلا ثبت شده است.',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
