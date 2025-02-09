<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory;
    protected $fillable = [
        'from_date',
        'to_date',
        'possible_visits',
        'title',
        'schedule_date_id',
        'room_id',
        'personnel_id',
        'medical_service_id',
        'is_appointable',
    ];

    // relations
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function service()
    {
        return $this->belongsTo(MedicalServices::class, 'medical_service_id');
    }
    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}
