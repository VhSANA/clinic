<?php

use App\PatientBillStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bill_patient_states', function (Blueprint $table) {
            $table->id();
            $table->enum('status', [
                PatientBillStatus::ISSUED->name,
                PatientBillStatus::PAID->name,
                PatientBillStatus::RETURNED->name,
            ])->default(PatientBillStatus::ISSUED->name);
            $table->timestamps();
        });

        // add bill paid or unpaid status column to bill_patient_invoices
        Schema::table('bill_patient_invoice', function (Blueprint $table) {
            $table->foreignId('payment_status_id')->constrained('bill_patient_states', 'id')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bill_patient_invoice', function (Blueprint $table) {
            $table->dropColumn('payment_status_id');
        });
        Schema::dropDatabaseIfExists('bill_patient_states');
    }
};
