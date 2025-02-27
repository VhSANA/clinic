<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    /** @use HasFactory<\Database\Factories\InsuranceFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'icon',
        'description'
    ];

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
