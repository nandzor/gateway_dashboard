<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'histories';

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
        'trx_date' => 'datetime',
        'price' => 'decimal:3',
        'duration' => 'double',
        'is_charge' => 'integer',
        'is_local' => 'integer',
        'is_dashboard' => 'integer',
        'currency_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
     * Get the service/module
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'module_id');
    }

    /**
     * Scope: By client
     */
    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Scope: By date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('trx_date', [$startDate, $endDate]);
    }

    /**
     * Scope: Today's transactions
     */
    public function scopeToday($query)
    {
        return $query->whereDate('trx_date', today());
    }

    /**
     * Scope: This month's transactions
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('trx_date', now()->month)
                    ->whereYear('trx_date', now()->year);
    }

    /**
     * Scope: Charged transactions
     */
    public function scopeCharged($query)
    {
        return $query->where('is_charge', 1);
    }

    /**
     * Scope: Free transactions
     */
    public function scopeFree($query)
    {
        return $query->where('is_charge', 0);
    }

    /**
     * Scope: By transaction type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('trx_type', $type);
    }

    /**
     * Scope: By status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get transaction type name
     */
    public function getTransactionTypeNameAttribute(): string
    {
        $types = [
            1 => 'API Call',
            2 => 'Service Usage',
            3 => 'Data Transfer',
            4 => 'Storage',
            5 => 'Processing',
        ];

        return $types[$this->trx_type] ?? 'Unknown';
    }

    /**
     * Get client type name
     */
    public function getClientTypeNameAttribute(): string
    {
        $types = [
            1 => 'Individual',
            2 => 'Corporate',
            3 => 'Government',
            4 => 'NGO',
        ];

        return $types[$this->client_type] ?? 'Unknown';
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price ?? 0, 2);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        if ($this->duration < 60) {
            return number_format($this->duration, 2) . 's';
        } elseif ($this->duration < 3600) {
            return number_format($this->duration / 60, 2) . 'm';
        } else {
            return number_format($this->duration / 3600, 2) . 'h';
        }
    }

    /**
     * Check if transaction is successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'success' || $this->status === 'completed';
    }

    /**
     * Check if transaction is charged
     */
    public function isCharged(): bool
    {
        return $this->is_charge === 1;
    }

    /**
     * Check if transaction is local
     */
    public function isLocal(): bool
    {
        return $this->is_local === 1;
    }
}
