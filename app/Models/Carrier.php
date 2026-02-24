<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Carrier extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function flights(): HasMany
    {
        return $this->hasMany(Flight::class);
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $imagePath = 'logos/' . $attributes['id'] . '.png';
                // Check if the file exists in the 'public' disk
                if (Storage::disk('public')->exists($imagePath)) {
                    // Return the public URL for the image
                    return Storage::disk('public')->url($imagePath);
                }
                // Return the URL for the default 'no-cover' image
                return Storage::disk('public')->url('logos/no-logo.png');
            }
        );
    }

    protected $appends = ['image'];
}
