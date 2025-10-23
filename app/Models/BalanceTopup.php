<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BalanceTopup extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'amount',
        'previous_balance',
        'new_balance',
        'payment_method',
        'reference_number',
        'notes',
        'status',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:3',
        'previous_balance' => 'decimal:3',
        'new_balance' => 'decimal:3',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the client that owns the topup
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the user who processed the topup
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Pending topups
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Approved topups
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Rejected topups
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: Recent topups
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Scope: Search topups
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->whereHas('client', function ($clientQuery) use ($searchTerm) {
                $clientQuery->where('client_name', 'like', "%{$searchTerm}%");
            })
            ->orWhere('amount', 'like', "%{$searchTerm}%")
            ->orWhere('reference_number', 'like', "%{$searchTerm}%")
            ->orWhere('notes', 'like', "%{$searchTerm}%");
        });
    }

    /**
     * Check if topup is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if topup is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if topup is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if topup is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Approve the topup and update client balance
     */
    public function approve(?User $user = null): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        try {
            DB::transaction(function () use ($user) {
                // Get current balance
                $balance = Balance::where('client_id', $this->client_id)->first();
                if (!$balance) {
                    $balance = Balance::create([
                        'client_id' => $this->client_id,
                        'balance' => 0,
                        'quota' => 0
                    ]);
                }

                // Update balance
                $newBalance = $balance->balance + $this->amount;
                $balance->setBalance($newBalance);

                // Update topup record
                $this->update([
                    'status' => 'approved',
                    'previous_balance' => $balance->balance - $this->amount,
                    'new_balance' => $newBalance,
                    'processed_at' => now(),
                    'user_id' => $user ? $user->id : null,
                ]);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to approve topup: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Reject the topup
     */
    public function reject(?User $user = null, ?string $reason = null): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'processed_at' => now(),
            'user_id' => $user ? $user->id : null,
            'notes' => $reason ? ($this->notes ? $this->notes . "\n\nRejection reason: " . $reason : "Rejection reason: " . $reason) : $this->notes,
        ]);

        return true;
    }

    /**
     * Cancel the topup
     */
    public function cancel(?User $user = null, ?string $reason = null): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->update([
            'status' => 'cancelled',
            'processed_at' => now(),
            'user_id' => $user ? $user->id : null,
            'notes' => $reason ? ($this->notes ? $this->notes . "\n\nCancellation reason: " . $reason : "Cancellation reason: " . $reason) : $this->notes,
        ]);

        return true;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui'
        };
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
            'debit_card' => 'Kartu Debit',
            'e_wallet' => 'E-Wallet',
            'other' => 'Lainnya',
            default => 'Tidak Diketahui'
        };
    }
}
