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
        Schema::create('treatment_action_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('info')->nullable();
            $table->enum('type', ['text', 'textarea', 'number', 'list', 'richtext'])->default('text');
            $table->foreignId('treatment_action_type_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('validation_message')->nullable();
            $table->string('validation_expression')->nullable();
            $table->timestamps();
        });

        Schema::table('treatment_action_types', function (Blueprint $table) {
            $table->foreignId('treatment_action_attribute_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropDatabaseIfExists('treatment_action_attributes');
    }
};
