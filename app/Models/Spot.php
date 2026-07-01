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
     * Priority: image_url (Supabase or external) → legacy image_path (local storage) → null
     */
    public function getDisplayImageAttribute(): ?string
    {
        // Supabase / external URL stored directly in image_url
        if ($this->image_url) {
            return $this->image_url;
        }

        // Legacy local storage path (image_path)
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }

        return null;
    }

    /**
     * Scope: filter only sponsored spots for the dashboard featured section.
     */
    public function scopeSponsored($query)
    {
        return $query->where('is_sponsored', true);
    }
}
