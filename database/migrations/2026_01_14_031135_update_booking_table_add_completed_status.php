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
            // Change the status column to include 'Completed' option
            $table->enum('status', ['Available', 'Booked', 'Pending', 'Completed'])->default('Pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            // Revert the status column back to original options
            $table->enum('status', ['Available', 'Booked', 'Pending'])->default('Pending')->change();
        });
    }
};
