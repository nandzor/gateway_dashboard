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
