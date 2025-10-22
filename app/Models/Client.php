<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'address',
        'contact',
        'type',
        'ak',
        'sk',
        'avkey_iv',
        'avkey_pass',
        'service_module',
        'is_active',
        'service_allow',
        'white_list',
        'module_40',
        'is_staging',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_active' => 'integer',
        'is_staging' => 'integer',
        'type' => 'integer',
        'service_module' => 'integer',
    ];

    protected $appends = [
        'service_allow_name',
        'service_module_name',
    ];

    /**
     * Get client credentials
     * TODO: Uncomment when ClientCredential model is created
     */
    // public function credentials()
    // {
    //     return $this->hasMany(\App\Models\ClientCredential::class);
    // }

    /**
     * Get client balances
     */
    public function balances()
    {
        return $this->hasMany(\App\Models\Balance::class);
    }

    /**
     * Get client histories
     * TODO: Uncomment when History model is created
     */
    // public function histories()
    // {
    //     return $this->hasMany(\App\Models\History::class);
    // }

    /**
     * Get assigned services through service_assign table
     */
    public function serviceAssigns()
    {
        return $this->hasMany(ClientServiceAssignment::class);
    }

    /**
     * Get assigned services
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_assign');
    }

    /**
     * Get the configured service module (single) via foreign key `service_module`.
     */
    public function serviceModule()
    {
        return $this->belongsTo(Service::class, 'service_module');
    }

    /**
     * Get whitelist IPs
     * TODO: Uncomment when WhitelistIpApi model is created
     */
    // public function whitelistIps()
    // {
    //     return $this->hasMany(\App\Models\WhitelistIpApi::class);
    // }

    /**
     * Scope: Active clients
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope: Inactive clients
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', 0);
    }

    /**
     * Scope: Staging clients
     */
    public function scopeStaging($query)
    {
        return $query->where('is_staging', 1);
    }

    /**
     * Scope: Production clients
     */
    public function scopeProduction($query)
    {
        return $query->where('is_staging', 0);
    }

    /**
     * Check if client is active
     */
    public function isActive(): bool
    {
        return $this->is_active === 1;
    }

    /**
     * Check if client is staging
     */
    public function isStaging(): bool
    {
        return $this->is_staging === 1;
    }

    /**
     * Soft delete client by setting is_active to 0
     */
    public function softDelete(): bool
    {
        return $this->update(['is_active' => 0]);
    }

    /**
     * Restore soft deleted client by setting is_active to 1
     */
    public function restore(): bool
    {
        return $this->update(['is_active' => 1]);
    }

    /**
     * Get client type name
     */
    public function getTypeNameAttribute(): string
    {
        $types = [
            1 => 'Prepaid',
            2 => 'Postpaid',
        ];

        return $types[$this->type] ?? 'Unknown';
    }

    /**
     * Get client type badge variant
     */
    public function getTypeBadgeVariantAttribute(): string
    {
        $variants = [
            1 => 'purple',    // Prepaid - Cyan (menggunakan info yang sudah ada)
            2 => 'indigo', // Postpaid - Green (menggunakan success yang sudah ada)
        ];

        return $variants[$this->type] ?? 'secondary';
    }

    /**
     * Check if client is prepaid
     */
    public function isPrepaid(): bool
    {
        return $this->type === 1;
    }

    /**
     * Check if client is postpaid
     */
    public function isPostpaid(): bool
    {
        return $this->type === 2;
    }

    /**
     * Get service assignments as array of service IDs
     */
    public function getServiceAssignmentsAttribute(): array
    {
        return $this->serviceAssigns()->pluck('service_id')->toArray();
    }

    /**
     * Get assigned service names as array (from service_assigns -> services.name)
     */
    public function getServiceAllowNameAttribute(): array
    {
        return $this->services()->pluck('name')->toArray();
    }

    /**
     * Get service module name instead of ID.
     */
    public function getServiceModuleNameAttribute(): ?string
    {
        return $this->serviceModule ? $this->serviceModule->name : null;
    }

    /**
     * Get current balance
     */
    public function getCurrentBalance()
    {
        return $this->balances()->latest()->first();
    }
}
