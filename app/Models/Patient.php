<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'family',
        'full_name',
        'father_name',
        'national_code',
        'is_foreigner',
        'passport_code',
        'mobile',
        'phone',
        'address',
        'birth_date',
        'gender',
        'relation_status',
        'insurance_number',
        'insurance_id',
    ];

    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }
    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }
}
