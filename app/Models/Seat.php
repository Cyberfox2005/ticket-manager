<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Shared\Infrastructure\Auditable;
use App\Modules\Shared\Infrastructure\TenantScoped;

class Seat extends Model
{
    use HasFactory, SoftDeletes, Auditable, TenantScoped;

    protected $fillable = ['venue_id', 'section', 'row', 'number', 'status'];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}
