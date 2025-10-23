<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'symbol',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the price masters for this currency
     */
    public function priceMasters()
    {
        return $this->hasMany(PriceMaster::class);
    }

    /**
     * Get the price customs for this currency
     */
    public function priceCustoms()
    {
        return $this->hasMany(PriceCustom::class);
    }

    /**
     * Scope for active currencies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive currencies
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Check if currency is active
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Soft delete currency by setting is_active to false
     */
    public function softDelete(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Restore soft deleted currency by setting is_active to true
     */
    public function restore(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Get formatted created at date
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('M d, Y - h:i A') : 'N/A';
    }

    /**
     * Get formatted updated at date
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('M d, Y - h:i A') : 'N/A';
    }

    /**
     * Get short formatted created at date
     */
    public function getShortCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('M d, Y') : 'N/A';
    }
}
