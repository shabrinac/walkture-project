<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotographerDirectory extends Model
{
    use HasFactory;

    protected $table = 'photographers_directory';

    protected $fillable = [
        'full_name',
        'specialty',
        'avatar_url',
        'portfolio_url',
        'whatsapp_link',
        'instagram_link',
        'is_active',
        'paid_until',
        'pricing_packages',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'paid_until'       => 'datetime',
        'pricing_packages' => 'array',
    ];

    /**
     * Scope: return only active photographers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: return featured/paid photographers (paid_until is in the future).
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('paid_until')
                           ->orWhere('paid_until', '>=', now());
                     });
    }
}
