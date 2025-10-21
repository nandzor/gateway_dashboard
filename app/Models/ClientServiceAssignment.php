<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientServiceAssignment extends Model
{
    use HasFactory;

    protected $table = 'service_assign';

    protected $fillable = [
        'client_id',
        'service_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the client that owns the assignment
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the service that owns the assignment
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
