<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Passenger extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) =>  $attributes['firstname'] . ' ' . $attributes['lastname'],
        );
    }

    protected $appends = ['full_name'];
}
