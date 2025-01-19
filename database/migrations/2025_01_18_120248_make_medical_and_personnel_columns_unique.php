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
        Schema::table('medical_services_personnel', function (Blueprint $table) {
            $table->unique(['personnel_id', 'medical_services_id'], 'personnel_service_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_services_personnel', function (Blueprint $table) {
            $table->dropUnique(['personnel_id', 'medical_services_id']);
        });
    }
};
