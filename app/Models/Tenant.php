<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // Family name
        'currency', // Currency display setting
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }


    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }


    public function owner()
    {
        return $this->users()->where('role', 'owner')->first();
    }
}
