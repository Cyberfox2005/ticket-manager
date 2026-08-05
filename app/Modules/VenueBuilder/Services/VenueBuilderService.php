<?php

namespace App\Modules\VenueBuilder\Services;

use App\Models\Venue;

class VenueBuilderService
{
    public function saveLayout(int $venueId, array $layout): void
    {
        $venue = Venue::findOrFail($venueId);

        // Validation of layout schema would happen here

        $venue->update([
            'seating_map' => $layout,
            'capacity' => $this->calculateCapacity($layout)
        ]);
    }

    protected function calculateCapacity(array $layout): int
    {
        $count = 0;
        foreach ($layout['elements'] ?? [] as $element) {
            if ($element['type'] === 'section') {
                $count += count($element['seats'] ?? []);
            }
        }
        return $count;
    }

    public function getLayout(int $venueId): array
    {
        $venue = Venue::findOrFail($venueId);
        return $venue->seating_map ?? ['elements' => [], 'canvas' => ['width' => 2000, 'height' => 2000]];
    }
}
