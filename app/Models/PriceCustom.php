<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceCustom extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'client_id',
        'price_custom',
        'is_active',
        'currency_id',
    ];

    protected $casts = [
        'price_custom' => 'decimal:3',
        'is_active' => 'boolean',
    ];

    /**
     * Get the service that owns the price custom
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'module_id');
    }

    /**
     * Get the client that owns the price custom
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the currency that owns the price custom
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Scope for active price customs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive price customs
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Check if price custom is active
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Soft delete price custom by setting is_active to false
     */
    public function softDelete(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Restore soft deleted price custom by setting is_active to true
     */
    public function restore(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Get formatted price with currency symbol
     */
    public function getFormattedPriceAttribute()
    {
        $symbol = 'Rp'; // Default currency symbol
        return $symbol . ' ' . number_format($this->price_custom ?? 0, 0, ',', '.');
    }

    /**
     * Get service name
     */
    public function getServiceNameAttribute()
    {
        return $this->service?->name ?? 'N/A';
    }

    /**
     * Get client name
     */
    public function getClientNameAttribute()
    {
        return $this->client?->client_name ?? 'N/A';
    }

    /**
     * Get currency name
     */
    public function getCurrencyNameAttribute()
    {
        return $this->currency?->name ?? 'N/A';
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
