<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    protected $guarded = [];

    /** Automatically include display_image in JSON/array output */
    protected $appends = ['display_image'];

    protected $casts = [
        'is_sponsored'    => 'boolean',
        'latitude'        => 'float',
        'longitude'       => 'float',
        'polygon_geojson' => 'array',
        'route_geojson'   => 'array',
    ];

    /**
     * Get the display image URL.
     * Prefers locally uploaded image_path, falls back to external image_url.
     */
    public function getDisplayImageAttribute(): ?string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return $this->image_url ?: null;
    }

    /**
     * Scope: filter only sponsored spots for the dashboard featured section.
     */
    public function scopeSponsored($query)
    {
        return $query->where('is_sponsored', true);
    }
}
