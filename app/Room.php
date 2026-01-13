<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{

    // 
    protected $table = 'rooms';
    protected $primaryKey = 'room_id';
    protected $fillable = ['room_number', 'room_type', 'price', 'status'];


    // One room can have many bookings
    public function bookings(){

    return $this->hasMany(Booking::class, 'room_id');

    }
}
