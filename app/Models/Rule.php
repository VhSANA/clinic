<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    /** @use HasFactory<\Database\Factories\RuleFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'persian_title',
        'description',
        'rule_icon',
    ];

    // relation
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
