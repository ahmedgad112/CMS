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
        // First, remove any duplicate phone numbers (keep the oldest record)
        \DB::statement('
            DELETE p1 FROM patients p1
            INNER JOIN patients p2 
            WHERE p1.id > p2.id 
            AND p1.phone_number = p2.phone_number
        ');

        Schema::table('patients', function (Blueprint $table) {
            $table->unique('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropUnique(['phone_number']);
        });
    }
};
