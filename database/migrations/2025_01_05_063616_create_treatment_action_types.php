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
        Schema::create('treatment_action_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('sub_title');
            $table->string('icon')->nullable();
            $table->string('allowed_number');
            $table->timestamps();
        });

        Schema::table('treatment_actions', function (Blueprint $table) {
            $table->foreignId('treatment_action_type_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatment_actions', function (Blueprint $table) {
            $table->dropColumn('treatment_action_type_id');
        });
        Schema::dropDatabaseIfExists('treatment_action_types');
    }
};
