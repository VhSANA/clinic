<?php

namespace App;

enum PatientBillStatus: string
{
    case ISSUED = 'صادر شده';
    case PAID = 'پرداخت شده';
    case RETURNED = 'عودت شده';
}
