<?php


namespace App;

// Use this specific class for Auth
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;

class Account extends Authenticatable
{
    use Notifiable;

    protected $table = 'accounts'; // Ensure it points to your table
    protected $primaryKey = 'account_id';
    
    protected $fillable = [
        'last_name', 
        'first_name', 
        'email', 
        'password', 
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}

