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
        // No changes needed - we'll keep using string role in users table
        // This migration is kept for future use if needed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
