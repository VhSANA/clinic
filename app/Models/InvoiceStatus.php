<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceStatus extends Model
{
    protected $table = 'bill_patient_states';

    // relation
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
