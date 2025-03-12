<?php

namespace App;

enum AppointmentQueueStatus: string
{
    CASE IN_QUEUE = "حضور در صف";
    CASE RECIEVING_SERVICE = "درحال دریافت خدمت";
}
