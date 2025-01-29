<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $table = 'schedule_dates';

    protected $fillable = [
        'tilte',
        'is_holiday',
        'description',
        'date',
    ];

    // relation
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'schedule_date_id');
    }
}
