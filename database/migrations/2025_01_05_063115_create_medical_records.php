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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_folder_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('personnel_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('appointment_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('service_start_time');
            $table->timestamp('service_end_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropDatabaseIfExists('medical_records');
    }
};
