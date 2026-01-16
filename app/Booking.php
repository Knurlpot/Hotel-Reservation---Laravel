<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    protected $table = 'booking';
    protected $primaryKey = 'booking_id';
    protected $fillable = ['room_id', 'account_id', 'guest_name', 'check_in_date', 'check_out_date', 'status', 'total_amount'];

    // Connect back to the Room
    public function room() {
        return $this->belongsTo(Room::class, 'room_id');
    }

    // Connect back to the Account (Staff)
    public function account() {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
