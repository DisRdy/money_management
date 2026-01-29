<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'role',
        'avatar',
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
     * Relationships for UAS Demonstration
     * ====================================
     * These relationships demonstrate one-to-many relationships in Laravel
     */

    /**
     * Get the tenant (family) that owns this user.
     * Relationship: User belongs to Tenant (Many-to-One)
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get all categories created by this user.
     * Relationship: User has many Categories (One-to-Many)
     * UAS CORE: Demonstrates User → Category relationship
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get all transactions created by this user.
     * Relationship: User has many Transactions (One-to-Many)
     * UAS CORE: Demonstrates User → Transaction relationship
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Helper method to check if user is owner
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Helper method to check if user is wife
     */
    public function isWife(): bool
    {
        return $this->role === 'wife';
    }

    /**
     * Helper method to check if user is child
     */
    public function isChild(): bool
    {
        return $this->role === 'child';
    }

    /**
     * Get the settings for this user.
     * Relationship: User has one UserSetting (One-to-One)
     */
    public function settings()
    {
        return $this->hasOne(UserSetting::class);
    }

    /**
     * Get the user's theme preference.
     * Auto-creates settings record if missing.
     */
    public function getTheme(): string
    {
        if (!$this->settings) {
            $this->settings()->create(['theme' => 'system']);
            $this->refresh();
        }
        return $this->settings->theme;
    }
}
