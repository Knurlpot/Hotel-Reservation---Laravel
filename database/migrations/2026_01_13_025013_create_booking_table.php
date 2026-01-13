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
        Schema::create('booking', function (Blueprint $table) {
            $table->id('booking_id');
            $table->foreignId('room_id')->constrained('rooms', 'room_id')->onDelete('cascade');
            $table->foreignId('account_id')->constrained('accounts', 'account_id')->onDelete('cascade');
            $table->string('guest_name');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->enum('status', ['Available', 'Booked', 'Pending'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
