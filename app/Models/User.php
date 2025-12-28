<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'email',
        'user_type',
        'first_name',
        'last_name',
        'phone',
        'is_active'
    ];

    protected $hidden = [
        'password',
    ];

    // Tell Laravel which field to use for password
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Relationships
    public function driver()
    {
        return $this->hasOne(Driver::class);
    }

    public function files()
    {
        return $this->hasMany(File::class, 'uploaded_by');
    }

    public function processedPayments()
    {
        return $this->hasMany(PaymentRecord::class, 'processed_by');
    }
}