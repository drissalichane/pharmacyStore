<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cart[] $cart
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_GUEST = 'guest';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the cart items for the user.
     */
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the total value of the user's cart.
     */
    public function getCartTotalAttribute()
    {
        return $this->cart->sum(function ($item) {
            return $item->total_price;
        });
    }

    /**
     * Get the total number of items in the user's cart.
     */
    public function getCartItemCountAttribute()
    {
        return $this->cart->sum('quantity');
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is a regular user
     */
    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * Check if user is a guest (not authenticated)
     * Note: This is typically checked via Auth::guest()
     */
    public function isGuest(): bool
    {
        return $this->role === self::ROLE_GUEST;
    }

    /**
     * Scope to get admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    /**
     * Scope to get regular users
     */
    public function scopeUsers($query)
    {
        return $query->where('role', self::ROLE_USER);
    }
}
