<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentsStatus extends Model
{
    protected $table = 'appointment_status';
    protected $fillable = [
        'status'
    ];

    // relation
    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }
}
