<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'balance',
        'quota',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'balance' => 'decimal:3',
        'quota' => 'integer',
    ];

    /**
     * Get the client that owns the balance
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Scope: Positive balances
     */
    public function scopePositive($query)
    {
        return $query->where('balance', '>', 0);
    }

    /**
     * Scope: Zero balances
     */
    public function scopeZero($query)
    {
        return $query->where('balance', 0);
    }

    /**
     * Scope: Negative balances
     */
    public function scopeNegative($query)
    {
        return $query->where('balance', '<', 0);
    }

    /**
     * Check if balance is positive
     */
    public function isPositive(): bool
    {
        return $this->balance > 0;
    }

    /**
     * Check if balance is zero
     */
    public function isZero(): bool
    {
        return $this->balance == 0;
    }

    /**
     * Check if balance is negative
     */
    public function isNegative(): bool
    {
        return $this->balance < 0;
    }

    /**
     * Add amount to balance
     */
    public function addBalance(float $amount): bool
    {
        return $this->update(['balance' => $this->balance + $amount]);
    }

    /**
     * Subtract amount from balance
     */
    public function subtractBalance(float $amount): bool
    {
        return $this->update(['balance' => $this->balance - $amount]);
    }

    /**
     * Set balance to specific amount
     */
    public function setBalance(float $amount): bool
    {
        return $this->update(['balance' => $amount]);
    }

    /**
     * Add quota
     */
    public function addQuota(int $quota): bool
    {
        $this->quota += $quota;
        return $this->save();
    }

    /**
     * Subtract quota
     */
    public function subtractQuota(int $quota): bool
    {
        $this->quota -= $quota;
        return $this->save();
    }

    /**
     * Set quota to specific amount
     */
    public function setQuota(int $quota): bool
    {
        $this->quota = $quota;
        return $this->save();
    }
}
