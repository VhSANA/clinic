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
    public function appointmentStatus()
    {
        return $this->belongsTo(AppointmentsStatus::class, 'appointment_status_id');
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
