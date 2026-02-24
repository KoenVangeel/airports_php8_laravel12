<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flight extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // anders werkt de checkbox niet als je een update doet en in het model komt
    protected $casts = [
        'boarding' => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function flightstatus(): BelongsTo
    {
        return $this->belongsTo(Flightstatus::class)->withDefault();
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class)->withDefault();
    }

    public function from_airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'from_airport_id', 'id')->withDefault();
    }

    public function to_airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'to_airport_id', 'id')->withDefault();
    }

    protected function fullDepartureTime(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) =>  Carbon::parse($attributes['etd'])->format('d/m/Y H:i'),
        );
    }

    protected function fullDepartureDate(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) =>  Carbon::parse($attributes['etd'])->format('d/m/Y'),
        );
    }

    protected function shortDepartureTime(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) =>  Carbon::parse($attributes['etd'])->format('H:i'),
        );
    }

    protected function shortArrivalTime(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) =>  Carbon::parse($attributes['eta'])->format('H:i'),
        );
    }

    protected function fullArrivalTime(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) =>  Carbon::parse($attributes['eta'])->format('d/m/Y H:i'),
        );
    }

    protected function fullArrivalDate(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) =>  Carbon::parse($attributes['eta'])->format('d/m/Y'),
        );
    }
    protected function boardingText(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) =>  $attributes['boarding'] ? 'Boarding' : '',
        );
    }

    protected function duration(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) =>  $this->getDateTimeDifference(
                Carbon::createFromFormat('Y-m-d H:i:s', $attributes['etd']),
                Carbon::createFromFormat('Y-m-d H:i:s', $attributes['eta'])),
        );
    }

    protected function getDateTimeDifference(Carbon $dateTime1, Carbon $dateTime2)
    {
        // Calculate the difference between the two dates in hours and minutes
        $hours = sprintf("%02d", $dateTime1->diffInHours($dateTime2));
        $minutes = sprintf("%02d", $dateTime1->diffInMinutes($dateTime2) % 60);

        // Return the difference as a string in the format "H hours M minutes"
        return $hours . 'h' . $minutes . 'm';
    }

    protected $appends = ['full_departure_time', 'full_departure_date', 'full_arrival_time', 'full_arrival_date', 'short_departure_time', 'short_arrival_time', 'boarding_text', 'duration'];

    public function scopeSearchFlightnumber($query, $search = '%') : Builder
        // use as Flights::searchFlightNumber(...
        // Run Laravel > Generate Helper Code again VOOR HELP IN PHPStorm
    {
        return $query->where('number', 'like', "{$search}%");
    }

}
