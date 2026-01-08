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
        // Update prescriptions table - remove visit_id, add appointment_id
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['visit_id']);
            $table->dropColumn('visit_id');
            $table->foreignId('appointment_id')->nullable()->after('id')->constrained('appointments')->onDelete('cascade');
        });

        // Update invoices table - remove visit_id, add appointment_id
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['visit_id']);
            $table->dropColumn('visit_id');
            $table->foreignId('appointment_id')->nullable()->after('patient_id')->constrained('appointments')->onDelete('set null');
        });

        // Drop visits table
        Schema::dropIfExists('visits');
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
