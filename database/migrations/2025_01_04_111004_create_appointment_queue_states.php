<?php

use App\AppointmentQueueStatus;
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
        Schema::create('appointment_queue_states', function (Blueprint $table) {
            $table->id();
            $table->enum('', [
                AppointmentQueueStatus::IN_QUEUE->name,
                AppointmentQueueStatus::RECIEVING_SERVICE->name,
            ])->default(AppointmentQueueStatus::IN_QUEUE->name);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropDatabaseIfExists('appointment_queue_states');
    }
};
