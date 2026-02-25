<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
    /* Storage::disk('public')->exists($imagePath); WERKT NIET OP COMBELL
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
    */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn () =>
            asset(
                file_exists(storage_path('app/public/logos/' . $this->id . '.png'))
                    ? 'storage/logos/' . $this->id . '.png'
                    : 'storage/logos/no-logo.png'
            )
        );
    }

    protected $appends = ['image'];

    public function scopeSearchNameOrCode($query, $search = '%') : Builder
        // use as Carriers::searchNameOrCode(...
        // Run Laravel > Generate Helper Code again VOOR HELP IN PHPStorm
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('code', 'like', "%{$search}%");
    }

    public function scopeLogoExists($query, $exists = true)
    {
        // Get all airline ids that have logos based on file existence
        $ids_with_logo = $query->pluck('id')->filter(function ($id) {
            return Storage::disk('public')->exists('logos/' . $id . '.png');
        })->values()->all();

        // If $exists is true, we want airlines WITH logo (where id is in $ids_with_logo)
        // If $exists is false, we want airlines WITHOUT logo (where mid is NOT in $ids_with_logo)
        $method = $exists ? 'whereIn' : 'whereNotIn';

        return $query->$method('id', $ids_with_logo);
    }

}
