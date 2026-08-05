<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = ['venue_id', 'section', 'row', 'number', 'status'];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}
