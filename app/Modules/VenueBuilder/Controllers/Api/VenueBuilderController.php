<?php

namespace App\Modules\VenueBuilder\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\VenueBuilder\Services\VenueBuilderService;
use App\Modules\Shared\Infrastructure\ApiResponse;
use Illuminate\Http\Request;

class VenueBuilderController extends Controller
{
    use ApiResponse;

    public function __construct(protected VenueBuilderService $venueBuilderService) {}

    /**
     * Get the seating layout for a venue.
     */
    public function getLayout(int $id)
    {
        try {
            $layout = $this->venueBuilderService->getLayout($id);
            return $this->successResponse($layout);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * Save / update the seating layout for a venue.
     */
    public function saveLayout(Request $request, int $id)
    {
        $request->validate([
            'elements' => 'required|array',
            'canvas'   => 'required|array',
            'canvas.width'  => 'required|integer|min:100',
            'canvas.height' => 'required|integer|min:100',
        ]);

        try {
            $this->venueBuilderService->saveLayout($id, $request->all());
            return $this->successResponse(null, 'Venue layout saved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}
