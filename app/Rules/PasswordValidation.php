<?php

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;

class PasswordValidation implements Rule
{
    private $error;

    public function passes($attribute, $value)
    {
        if (strlen($value) < 5) {
            $this->error = 'رمز عبور باید حداقل 5 کاراکتر باشد.';
            return false;
        }

        // if (!preg_match('/[a-zA-Z]/', $value)) {
        //     $this->error = 'رمز عبور باید حداقل شامل یک حرف انگلیسی باشد.';
        //     return false;
        // }

        if (!preg_match('/[0-9]/', $value)) {
            $this->error = 'رمز عبور باید حداقل شامل یک عدد باشد.';
            return false;
        }

        // if (!preg_match('/[A-Z]/', $value) || !preg_match('/[a-z]/', $value)) {
        //     $this->error = 'رمز عبور باید شامل حروف بزرگ و کوچک باشد.';
        //     return false;
        // }

        // if (!preg_match('/^[\x{0600}-\x{06FF}\s]+$/u', $value) || !preg_match('/[a-z]/', $value)) {
        //     $this->error = 'رمز عبور باید شامل حروف فارسی باشد.';
        //     return false;
        // }

        return true;
    }

    public function message()
    {
        return $this->error;
    }
}
