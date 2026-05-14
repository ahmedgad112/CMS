<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointment_requests', function (Blueprint $table) {
            $table->foreignId('specialization_id')->nullable()->after('service_type')
                ->constrained('specializations')->onDelete('set null');
            $table->foreignId('preferred_doctor_id')->nullable()->after('specialization_id')
                ->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('appointment_requests', function (Blueprint $table) {
            $table->dropForeign(['specialization_id']);
            $table->dropColumn('specialization_id');
            $table->dropForeign(['preferred_doctor_id']);
            $table->dropColumn('preferred_doctor_id');
        });
    }
};
