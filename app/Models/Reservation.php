<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Shared\Infrastructure\Auditable;
use App\Modules\Shared\Infrastructure\TenantScoped;

class Reservation extends Model
{
    use HasFactory, SoftDeletes, Auditable, TenantScoped;

    protected $fillable = ['user_id', 'event_id', 'seat_id', 'expires_at', 'status'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }
}
