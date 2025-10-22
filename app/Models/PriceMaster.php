<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'price_default',
        'is_active',
        'note',
        'currency_id',
    ];

    protected $casts = [
        'price_default' => 'decimal:3',
        'is_active' => 'boolean',
    ];

    /**
     * Get the service that owns the price master
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'module_id');
    }

    /**
     * Get the currency that owns the price master
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Scope for active price masters
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted price with currency symbol
     */
    public function getFormattedPriceAttribute()
    {
        $symbol = 'Rp'; // Default currency symbol
        return $symbol . ' ' . number_format($this->price_default ?? 0, 0, ',', '.');
    }

    /**
     * Get service name
     */
    public function getServiceNameAttribute()
    {
        return $this->service?->name ?? 'N/A';
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
