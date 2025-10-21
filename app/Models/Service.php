<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'is_active',
        'is_alert_zero',
    ];

    protected $casts = [
        'is_active' => 'integer',
        'is_alert_zero' => 'integer',
        'type' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope: Active services
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope: Inactive services
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', 0);
    }

    /**
     * Check if service is active
     */
    public function isActive(): bool
    {
        return $this->is_active === 1;
    }

    /**
     * Soft delete service (set is_active to 0)
     */
    public function softDelete(): bool
    {
        return $this->update(['is_active' => 0]);
    }

    /**
     * Restore service (set is_active to 1)
     */
    public function restore(): bool
    {
        return $this->update(['is_active' => 1]);
    }

    /**
     * Get service type name
     */
    public function getTypeNameAttribute(): string
    {
        $types = [
            1 => 'Internal',
            2 => 'External',
        ];

        return $types[$this->type] ?? 'Unknown';
    }

    /**
     * Get clients assigned to this service
     */
    public function clients()
    {
        return $this->belongsToMany(Client::class, 'service_assign');
    }
}
