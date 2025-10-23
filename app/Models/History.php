<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_id',
        'client_type',
        'trx_id',
        'trx_type',
        'trx_date',
        'module_id',
        'price',
        'duration',
        'is_charge',
        'remote_ip',
        'is_local',
        'status',
        'trx_req',
        'node_id',
        'is_dashboard',
        'currency_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'client_id' => 'integer',
        'client_type' => 'integer',
        'trx_type' => 'integer',
        'trx_date' => 'datetime',
        'module_id' => 'integer',
        'price' => 'decimal:3',
        'duration' => 'float',
        'is_charge' => 'integer',
        'is_local' => 'integer',
        'node_id' => 'integer',
        'is_dashboard' => 'integer',
        'currency_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope: Successful transactions
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope: Failed transactions
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope: Pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: By transaction type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('trx_type', $type);
    }

    /**
     * Scope: By client type
     */
    public function scopeByClientType($query, $clientType)
    {
        return $query->where('client_type', $clientType);
    }

    /**
     * Scope: Charged transactions
     */
    public function scopeCharged($query)
    {
        return $query->where('is_charge', 1);
    }

    /**
     * Scope: Local transactions
     */
    public function scopeLocal($query)
    {
        return $query->where('is_local', 1);
    }

    /**
     * Get the user that owns the history
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the client that owns the history
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the service/module that owns the history
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'module_id');
    }

    /**
     * Get the currency that owns the history
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get status badge variant
     */
    public function getStatusBadgeVariantAttribute(): string
    {
        return match($this->status) {
            'success' => 'success',
            'failed' => 'danger',
            'pending' => 'warning',
            'error' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'success' => 'Success',
            'failed' => 'Failed',
            'pending' => 'Pending',
            'error' => 'Error',
            default => ucfirst($this->status ?? 'Unknown')
        };
    }

    /**
     * Get transaction type display name
     */
    public function getTransactionTypeDisplayAttribute(): string
    {
        return match($this->trx_type) {
            1 => 'Credit',
            2 => 'Debit',
            3 => 'Refund',
            4 => 'Adjustment',
            default => 'Type ' . $this->trx_type
        };
    }

    /**
     * Get client type display name
     */
    public function getClientTypeDisplayAttribute(): string
    {
        return match($this->client_type) {
            1 => 'Prepaid',
            2 => 'Postpaid',
            default => 'Type ' . $this->client_type
        };
    }

    /**
     * Get formatted price with currency
     */
    public function getFormattedPriceAttribute(): string
    {
        return \App\Helpers\NumberHelper::formatCurrency($this->price ?? 0);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        if ($this->duration === null) {
            return 'N/A';
        }

        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        } else {
            return sprintf('%02d:%02d', $minutes, $seconds);
        }
    }

    /**
     * Get charge status display
     */
    public function getChargeStatusDisplayAttribute(): string
    {
        return $this->is_charge ? 'Yes' : 'No';
    }

    /**
     * Get local status display
     */
    public function getLocalStatusDisplayAttribute(): string
    {
        return $this->is_local ? 'Local' : 'Remote';
    }

    /**
     * Get dashboard status display
     */
    public function getDashboardStatusDisplayAttribute(): string
    {
        return $this->is_dashboard ? 'Dashboard' : 'API';
    }
}
