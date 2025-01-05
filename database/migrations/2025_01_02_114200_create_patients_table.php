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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('family');
            $table->string('full_name');
            $table->string('father_name');
            $table->string('national_code');
            $table->boolean('is_foreigner')->default(false);
            $table->string('passport_code')->nullable();
            $table->string('mobile');
            $table->string('phone')->nullable();
            $table->string('address');
            $table->timestamp('birth_date');
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->enum('relation_status', ['married', 'single'])->default('single');
            $table->string('insurance_id');
            $table->string('insurance_number');
            $table->unique(['national_code', 'passport_code']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
