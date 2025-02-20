<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'bill_patient_invoice';

    protected $fillable = [
        'name',
        'family',
        'national_code',
        'patient_mobile',
        'appointment_date',
        'appointment_id',
        'insurance_name',
        'insurance_number',
        'insurance_id',
        'total',
        'discount',
        'insurance_cost',
        'total_to_pay',
        'paid_amount',
        'user_id',
        'user_name',
        'invoice_number',
        'estimated_service_time',
        'line_index',
        'is_foreginer',
        'passport_code',
        'payment_status_id',
    ];

    // relations
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function invoiceStatus()
    {
        return $this->belongsTo(InvoiceStatus::class, 'payment_status_id');
    }

    public function invoiceDetails()
    {
        return $this->hasOne(InvoiceDetails::class);
    }
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
}
