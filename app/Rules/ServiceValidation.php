<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ServiceValidation implements ValidationRule
{
    protected $request;
    protected $ignoreId;

    public function __construct($request, $ignoreId = null)
    {
        $this->request = $request;
        $this->ignoreId = $ignoreId;
    }
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
                    Rule::exists('medical_services', 'id'),
                    // this code prevents from duplicate insert into DB
                    Rule::unique('medical_services_personnel', 'medical_services_id')->where(function ($query) {
                        return $query->where('personnel_id', $this->request->personnel);
                    })->ignore($this->ignoreId)
                ],
            ],
            [
                'required' => 'انتخاب خدمات درمانی الزامیست.',
                'exists' => 'خدمات درمانی یافت نشد.',
                    'unique' => 'این خدمت درمانی قبلاً به این پرسنل اختصاص داده شده است.'
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
