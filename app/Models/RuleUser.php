<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleUser extends Model
{
    /** @use HasFactory<\Database\Factories\RuleUserFactory> */
    use HasFactory;
    protected $table = 'rule_user';
    protected $fillable = [
        'user_id',
        'rule_id',
    ];

    public $timestamps = false;
}
