<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update prescriptions table
        Schema::table('prescriptions', function (Blueprint $table) {
            // نستخدم try/catch أو نفحص الـ Index للتأكد من عدم توقف الـ Migration
            if ($this->hasForeignKey('prescriptions', 'prescriptions_visit_id_foreign')) {
                $table->dropForeign(['visit_id']);
            }
            
            if (Schema::hasColumn('prescriptions', 'visit_id')) {
                $table->dropColumn('visit_id');
            }

            $table->foreignId('appointment_id')->nullable()->after('id')
                  ->constrained('appointments')->onDelete('cascade');
        });

        // 2. Update invoices table
        Schema::table('invoices', function (Blueprint $table) {
            if ($this->hasForeignKey('invoices', 'invoices_visit_id_foreign')) {
                $table->dropForeign(['visit_id']);
            }

            if (Schema::hasColumn('invoices', 'visit_id')) {
                $table->dropColumn('visit_id');
            }

            $table->foreignId('appointment_id')->nullable()->after('patient_id')
                  ->constrained('appointments')->onDelete('set null');
        });

        // 3. Drop visits table
        Schema::dropIfExists('visits');
    }

    /**
     * Helper function to check if a foreign key exists.
     */
    protected function hasForeignKey($table, $foreignKeyName): bool
    {
        $conn = Schema::getConnection();
        $dbName = $conn->getDatabaseName();
        
        $foreignKeys = $conn->select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = ? 
            AND TABLE_NAME = ? 
            AND CONSTRAINT_NAME = ?
        ", [$dbName, $table, $foreignKeyName]);

        return count($foreignKeys) > 0;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate visits table
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->unique()->constrained('appointments')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->date('visit_date');
            $table->text('diagnosis')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Revert invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
            $table->dropColumn('appointment_id');
            $table->foreignId('visit_id')->nullable()->after('patient_id')->constrained('visits')->onDelete('set null');
        });

        // Revert prescriptions table
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
            $table->dropColumn('appointment_id');
            $table->foreignId('visit_id')->after('id')->constrained('visits')->onDelete('cascade');
        });
    }
};