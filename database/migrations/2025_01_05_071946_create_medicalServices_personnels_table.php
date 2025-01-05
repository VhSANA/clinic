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
        Schema::create('medicalService_personnel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_service_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('personnel_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('estimated_service_time');
            $table->string('service_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropDatabaseIfExists('medicalService_personnel');
    }
};
