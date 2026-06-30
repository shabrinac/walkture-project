<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $casts = [
        'is_premium' => 'boolean',
        'credits'    => 'integer',
        'created_at' => 'datetime',
    ];

    public function photographerDetail()
    {
        return $this->hasOne(PhotographerDetail::class, 'id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'client_id');
    }

    public function unlockedSpots()
    {
        return $this->hasMany(UnlockedSpot::class, 'user_id');
    }

    public function equipmentRentals()
    {
        return $this->hasMany(EquipmentRental::class, 'user_id');
    }

    public function digitalEphemera()
    {
        return $this->hasMany(DigitalEphemera::class, 'user_id');
    }

    public function financialTransactions()
    {
        return $this->hasMany(FinancialTransaction::class, 'user_id');
    }
}
