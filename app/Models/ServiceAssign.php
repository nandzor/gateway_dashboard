<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the client that owns the service assignment.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the service that is assigned.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
