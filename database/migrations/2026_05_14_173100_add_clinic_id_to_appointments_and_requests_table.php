<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('clinic_id')->nullable()->after('doctor_id')
                ->constrained('clinics')->nullOnDelete();
            $table->index('clinic_id');
        });

        Schema::table('appointment_requests', function (Blueprint $table) {
            $table->foreignId('preferred_clinic_id')->nullable()->after('preferred_doctor_id')
                ->constrained('clinics')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['clinic_id']);
            $table->dropIndex(['clinic_id']);
            $table->dropColumn('clinic_id');
        });

        Schema::table('appointment_requests', function (Blueprint $table) {
            $table->dropForeign(['preferred_clinic_id']);
            $table->dropColumn('preferred_clinic_id');
        });
    }
};
