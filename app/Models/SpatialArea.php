<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpatialArea extends Model
{
    protected $fillable = [
        'name',
        'type',
        'geo_data',
        'distance_or_area',
    ];

    protected $casts = [
        'geo_data'         => 'array',   // Cast JSONB to PHP array
        'distance_or_area' => 'float',
    ];
}
