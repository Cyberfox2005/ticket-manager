<?php

namespace App\Modules\Shared\Infrastructure;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => 'Success',
            'data' => parent::toArray($request),
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
