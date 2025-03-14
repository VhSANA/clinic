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
        Schema::create('bill_payments', function (Blueprint $table) {
            $table->id();
            $table->string('amount');
            $table->enum('payment_type', ['cash', 'card'])->default('cash');
            $table->text('description')->nullable();
            $table->foreignId('invoice_id')->constrained('bill_patient_invoice', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('user_name');
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });

        // add invoice_id column to both bill-patient-invoice-details table
        Schema::table('bill_patient_invoice_details', function (Blueprint $table) {
            $table->foreignId('invoice_id')->constrained('bill_patient_invoice', 'id')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropDatabaseIfExists('bill_payments');
    }
};
