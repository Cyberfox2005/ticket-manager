<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Shared\Infrastructure\TenantScoped;
use App\Modules\Shared\Infrastructure\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use HasFactory, TenantScoped, Auditable, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'name',
        'address',
        'city',
        'country',
        'capacity',
        'seating_map',
    ];

    protected $casts = [
        'seating_map' => 'array',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
