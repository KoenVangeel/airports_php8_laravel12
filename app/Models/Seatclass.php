<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seatclass extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
