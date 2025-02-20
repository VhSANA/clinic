<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'bill_payments';

    protected $fillable = [
        'amount',
        'payment_type',
        'description',
        'invoice_id',
        'user_name',
        'user_id',
    ];

    // relations
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
