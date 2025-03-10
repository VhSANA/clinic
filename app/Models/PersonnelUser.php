<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonnelUser extends Model
{
    /** @use HasFactory<\Database\Factories\PersonnelUserFactory> */
    use HasFactory;
    protected $table = 'personnel_user';
    protected $fillable = [ 'personnel_id', 'user_id' ];
    public $timestamps = false;
}
