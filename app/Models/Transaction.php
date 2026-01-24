<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        // Automatically set tenant_id and user_id
        static::creating(function (Transaction $transaction) {
            if (auth()->check()) {
                $transaction->tenant_id = auth()->user()->tenant_id;
                $transaction->user_id = auth()->id();
            }
        });
    }

    protected $fillable = [
        'tenant_id',
        'user_id',
        'category_id',
        'type',
        'amount',
        'transaction_date',
        'description',
        'notes',
    ];

    public function scopeVisibleTo($query, $user = null)
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return $query->whereRaw('1 = 0'); // No user, no data
        }

        // Owner sees everything in their tenant
        if ($user->isOwner()) {
            return $query->where('tenant_id', $user->tenant_id);
        }

        if ($user->isWife()) {
            return $query->where('tenant_id', $user->tenant_id);
        }

        // Child sees only their own transactions
        if ($user->isChild()) {
            return $query->where('user_id', $user->id);
        }

        // Fallback: user has no recognized role, show nothing
        return $query->whereRaw('1 = 0');
    }
    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }


    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }


    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }
}
