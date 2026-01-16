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
        Schema::table('rooms', function (Blueprint $table) {
            // Modify the status enum to include 'Booked'
            $table->enum('status', ['Available', 'Booked', 'Under Maintenance'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Revert to the original enum values
            $table->enum('status', ['Available', 'Under Maintenance'])->change();
        });
    }
};
