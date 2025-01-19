<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalServices extends Model
{
    /** @use HasFactory<\Database\Factories\MedicalServicesFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'icon',
        'display_in_list',
    ];

    // relations
    public function personnels()
    {
        return $this->belongsToMany(Personnel::class, 'medical_services_personnel')->withPivot('id','estimated_service_time', 'service_price');
    }
}
