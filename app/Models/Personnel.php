<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'personnel_code',
        'image_url',
        'user_id',
    ];

    // relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function medicalservices()
    {
        return $this->belongsToMany(MedicalServices::class, 'medical_services_personnel')->withPivot('id','estimated_service_time', 'service_price');
    }
    public function schedule()
    {
        return $this->hasOne(Schedule::class);
    }
}
