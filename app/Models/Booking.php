<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class)->withDefault();
    }

    public function seatclass(): BelongsTo
    {
        return $this->belongsTo(Seatclass::class)->withDefault();
    }

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class)->withDefault();
    }
}
