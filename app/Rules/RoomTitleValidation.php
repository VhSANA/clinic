<?php

namespace App\Rules;

use App\Models\Room;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoomTitleValidation implements ValidationRule
{
    public function __construct(public Room $room) {}
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
                    Rule::unique('rooms', 'title')->ignore($this->room->id)
                ],
            ],
            [
                'required' => 'وارد کردن نام اتاق الزامی است.',
                'string' => 'اتاق باید فقط بصورت متن باشد.',
                'min' => 'حداقل باید شامل دو کاراکتر باشد.',
                'max' => 'مقدار عنوان طولانی میباشد.',
                'unique' => 'نام اتاق قبلا ثبت شده است.'
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }
}
