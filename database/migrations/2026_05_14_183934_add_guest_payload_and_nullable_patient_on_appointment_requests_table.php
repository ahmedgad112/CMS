<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointment_requests', function (Blueprint $table) {
            // بنفحص الأول لو العلاقة موجودة قبل ما نمسحها عشان ما يطلعش Error 1091
            if ($this->hasForeignKey('appointment_requests', 'appointment_requests_patient_id_foreign')) {
                $table->dropForeign(['patient_id']);
            }
        });

        Schema::table('appointment_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_id')->nullable()->change();
            $table->json('guest_payload')->nullable()->after('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('appointment_requests', function (Blueprint $table) {
            if ($this->hasForeignKey('appointment_requests', 'appointment_requests_patient_id_foreign')) {
                $table->dropForeign(['patient_id']);
            }
            $table->dropColumn('guest_payload');
            $table->unsignedBigInteger('patient_id')->nullable(false)->change();
            $table->foreign('patient_id')->references('id')->on('patients')->cascadeOnDelete();
        });
    }

    /**
     * Helper function للتأكد من وجود الـ Foreign Key
     */
    protected function hasForeignKey($table, $foreignKeyName): bool
    {
        $conn = Schema::getConnection();
        $dbName = $conn->getDatabaseName();
        $foreignKeys = $conn->select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?
        ", [$dbName, $table, $foreignKeyName]);

        return count($foreignKeys) > 0;
    }
};