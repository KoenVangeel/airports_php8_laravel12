<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Airport extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function departure_flights(): HasMany
    {
        return $this->hasMany(Flight::class, 'from_airport_id', 'id');
    }

    public function arrival_flights(): HasMany
    {
        return $this->hasMany(Flight::class, 'to_airport_id', 'id');
    }

    public function airportstatus(): BelongsTo
    {
        return $this->belongsTo(Airportstatus::class)->withDefault();
    }

    protected function cityAndCode(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) =>  $attributes['city'] . ' (' . $attributes['code'] . ')',
        );
    }
    protected $appends = [ 'city_and_code'];

    public function scopeSearchCityOrCode($query, $search = '')
    {
        if (empty($search)) { // Avoid applying filter if search term is empty
            return $query;
        }

        $searchTerm = "%{$search}%";        // Ensure we are looking for the search term anywhere in the string
        return $query                       // Apply the WHERE (title LIKE ?) OR (artist LIKE ?) condition
        ->where('city', 'like', $searchTerm)
            ->orWhere('code', 'like', $searchTerm);
    }
}
