<?php

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
        Schema::create('bill_patient_invoice', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('family');
            $table->string('national_code');
            $table->string('patient_mobile');
            $table->timestamp('appointment_date');
            $table->foreignId('appointment_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('insurance_name');
            $table->string('insurance_number')->unique()->nullable();
            $table->foreignId('insurance_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('total');
            $table->string('discount')->default(0);
            $table->string('insurance_cost')->default(0);
            $table->string('total_to_pay');
            $table->string('paid_amount')->default(0);
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('user_name');
            $table->string('invoice_number')->autoIncrement()->unique();
            $table->string('estimated_service_time');
            // TODO فک کنم نوبت باشه پایینی
            $table->string('line_index')->default(0);
            $table->boolean('is_foreigner')->default(0);
            $table->string('passport_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropDatabaseIfExists('bill_patient_invoice');
    }
};
