<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'theme',
    ];

    /**
     * Get the user that owns this settings.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
