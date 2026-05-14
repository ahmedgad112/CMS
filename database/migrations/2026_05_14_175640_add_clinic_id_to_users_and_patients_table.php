<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('clinic_id')->nullable()->after('specialization_id')
                ->constrained('clinics')->nullOnDelete();
            $table->index('clinic_id');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->foreignId('clinic_id')->nullable()->after('created_by')
                ->constrained('clinics')->nullOnDelete();
            $table->index('clinic_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('clinic_id')->nullable()->after('appointment_id')
                ->constrained('clinics')->nullOnDelete();
            $table->index('clinic_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['clinic_id']);
            $table->dropIndex(['clinic_id']);
            $table->dropColumn('clinic_id');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['clinic_id']);
            $table->dropIndex(['clinic_id']);
            $table->dropColumn('clinic_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['clinic_id']);
            $table->dropIndex(['clinic_id']);
            $table->dropColumn('clinic_id');
        });
    }
};
