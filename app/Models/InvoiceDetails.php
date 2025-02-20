<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    protected $table = 'bill_patient_invoice_details';

    protected $fillable = [
        'medical_service_name',
        'medical_service_price',
        'medical_service_id',
        'personnel_id',
        'personnel_name',
        'personnel_code',
        'room_id',
        'estimated_service_time',
        'invoice_id',
    ];

    // relations
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
