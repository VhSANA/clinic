<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'introducer_id',
        'schedule_id',
        'appointment_status_id',
        'estimated_service_time',
        'description',
        'user_id',
        'appointment_type',
        'canceled_user_id',
        'canceled_date',
    ];

    // relations
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
