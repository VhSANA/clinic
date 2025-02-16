<?php

namespace App;

enum AppointmentStatus: string
{
    case INITIAL_REGISTER = 'ثبت اولیه';
    case FINAL_REGISTER = 'ثبت نهایی';
    case CANCELLED = 'کنسل شده';
    case TRANSFORMED = 'انتقال یافته';
    case COMPLETED = 'کامل شده';
    case IN_QUEUE = 'در صف';
    case RETURN_FROM_QUEUE = 'بازگشت از صف';
}
