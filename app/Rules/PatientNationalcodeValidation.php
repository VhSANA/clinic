<?php

namespace App\Rules;

use App\Models\Patient;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PatientNationalcodeValidation implements ValidationRule
{
    protected Request $request;
    protected Patient $patient;
    public function __construct($request, $patient = null) {
        $this->request = $request;
        $this->patient = $patient;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->request->is_foreigner === 'off') {
            $validator = Validator::make(
                [$attribute => $value],
                [
                    $attribute => [
                        Rule::requiredIf($this->request->is_foreigner === 'off'),
                        'size:10',
                        Rule::unique('patients', 'national_code')->ignore($this->patient->id, 'id')
                    ],
                ],
                [
                    'required' => 'وارد نمودن کد ملی بیمار الزامیست.',
                    'size' => 'کد ملی باید 10 رقم باشد.',
                    'unique' => 'کد ملی بیمار قبلا ثبت شده است.',
                ]
            );

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $fail($error);
                }
            }
        }
    }
}
