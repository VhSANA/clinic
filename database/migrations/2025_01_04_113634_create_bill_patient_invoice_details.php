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
        Schema::create('bill_patient_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->string('medical_service_name');
            $table->string('medical_service_price');
            $table->foreignId('medical_service_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('personnel_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('personnel_name');
            $table->string('personnel_code')->nullable();
            $table->foreignId('room_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('estimated_service_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropDatabaseIfExists('bill_patient_invoice_details');
    }
};
