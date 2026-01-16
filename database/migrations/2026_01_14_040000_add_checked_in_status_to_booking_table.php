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
        Schema::table('booking', function (Blueprint $table) {
            // Modify the status enum to include 'Checked-In'
            $table->enum('status', ['Available', 'Booked', 'Pending', 'Checked-In', 'Completed'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            // Revert to the original enum values
            $table->enum('status', ['Available', 'Booked', 'Pending', 'Completed'])->change();
        });
    }
};
