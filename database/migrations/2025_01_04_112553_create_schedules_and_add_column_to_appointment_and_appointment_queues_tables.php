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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('from_date');
            $table->string('to_date');
            $table->foreignId('personnel_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('medical_service_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('schedule_date_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_appointable')->default(1);
            $table->timestamps();
        });

        // add column to appointments
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('schedule_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });
        Schema::table('appointment_queues', function (Blueprint $table) {
            $table->foreignId('schedule_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropDatabaseIfExists('schedules');
    }
};
