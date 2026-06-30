<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Check if user has the 'user' role.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user has the 'admin' role.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the named dashboard route based on the user's role.
     * Used by RoleMiddleware to redirect unauthorized access.
     */
    public function dashboardRoute(): string
    {
        return match($this->role) {
            'admin' => 'admin.dashboard',
            default => 'user.dashboard',
        };
    }

    /**
     * Relationship: user → equipment rentals.
     */
    public function equipmentRentals()
    {
        return $this->hasMany(EquipmentRental::class, 'user_id');
    }
}
