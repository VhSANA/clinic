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
        Schema::create('treatment_action_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_action_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('treatment_action_attribute_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('value')->nullable();
            $table->text('extra_data')->nullable();
            $table->string('group')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropDatabaseIfExists('treatment_action_details');
    }
};
