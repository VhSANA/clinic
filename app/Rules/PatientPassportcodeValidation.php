<?php

namespace App\Rules;

use App\Models\Patient;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PatientPassportcodeValidation implements ValidationRule
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
        if ($this->request->is_foreigner === 'on') {
            if ($this->request->is_foreigner === 'on') {
                $validator = Validator::make(
                    [$attribute => $value],
                    [
                        $attribute => [
                            Rule::requiredIf($this->request->is_foreigner === 'on'),
                            'regex:/^[A-Z0-9]{8,9}$/i',
                            Rule::unique('patients', 'passport_code')->ignore($this->patient->id, 'id')
                        ],
                    ],
                    [
                        'required' => 'در صورت انتخاب تبعه خارجی؛ وارد نمودن شماره پاسپورت الزامیست.',
                        'regex' => 'شماره پاسپورت نامعتبر میباشد.',
                        'unique' => 'شماره پاسپورت بیمار قبلا ثبت شده است.',
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
}
