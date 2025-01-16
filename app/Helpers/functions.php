<?php

use App\Models\User;

if (! function_exists('profileImageFunction')) {
    function profileImageFunction(User $user)
    {
        return ($user->personnel == null) ? 'https://t4.ftcdn.net/jpg/05/49/98/39/360_F_549983970_bRCkYfk0P6PP5fKbMhZMIb07mCJ6esXL.jpg' : $user->personnel->image_url;
    }
}


// shorten description
if (! function_exists('substrDescription')) {
    function substrDescription($model) {
        if (empty($model->description)) {
            return '-';
        } else {
            if (strlen($model->description) > 50) {
                return substr($model->description, 0, 50) . " ...";
            } else {
                return $model->description;
            }
        }
    }
}
