<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'driver_id',
        'license_number',
        'license_expiry',
        'vehicle_type',
        'vehicle_plate',
        'hire_date',
        'status',
        'payment_status', 
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'hire_date' => 'date',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function paymentRecords()
    {
        return $this->hasMany(PaymentRecord::class);
    }
}