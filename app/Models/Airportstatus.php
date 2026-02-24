<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Airportstatus extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function airports(): HasMany
    {
        return $this->hasMany(Airport::class);
    }

    protected function name(): Attribute
    {
        return Attribute::make(
        // Accessor: Called when retrieving $airportstatus->name
            get: fn ($value) => ucfirst($value), // Capitalize first letter

            // Mutator: Called when setting $airportstatus->name = '...' before saving
            set: fn ($value) => strtolower($value) // Convert to lowercase
        );
    }
}
