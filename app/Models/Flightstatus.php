<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flightstatus extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function flights(): HasMany
    {
        return $this->hasMany(Flight::class);
    }
}
