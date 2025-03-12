<?php

use App\AppointmentStatus;
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
        Schema::create('appointment_status', function (Blueprint $table) {
            $table->id();
            $table->enum('status', [
                AppointmentStatus::INITIAL_REGISTER->name,
                AppointmentStatus::FINAL_REGISTER->name,
                AppointmentStatus::CANCELLED->name,
                AppointmentStatus::TRANSFORMED->name,
                AppointmentStatus::COMPLETED->name,
                AppointmentStatus::IN_QUEUE->name,
                AppointmentStatus::RETURN_FROM_QUEUE->name,
            ])->default(AppointmentStatus::INITIAL_REGISTER->name);
            $table->string('status');
            $table->timestamps();
        });

        // add appointment_status relation to appointments table
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('appointment_status_id')->constrained('appointment_status', 'id')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('appointment_status_id');
        });
        Schema::dropDatabaseIfExists('appointment_queue_status');
    }
};
