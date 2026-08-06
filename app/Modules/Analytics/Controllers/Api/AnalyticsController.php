<?php

namespace App\Modules\Analytics\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Analytics\Services\AnalyticsQueryService;
use App\Modules\Shared\Infrastructure\ApiResponse;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    use ApiResponse;

    public function __construct(protected AnalyticsQueryService $analyticsService) {}

    /**
     * Get revenue statistics for the current organization.
     */
    public function revenue(Request $request)
    {
        $request->validate([
            'period'   => 'nullable|string|regex:/^\d+d$/',
            'event_id' => 'nullable|integer|exists:events,id',
        ]);

        $period  = $request->get('period', '30d');
        $eventId = $request->get('event_id');

        $revenue = $this->analyticsService->getRevenueStats($period, $request->user(), $eventId);

        return $this->successResponse($revenue);
    }

    /**
     * Get conversion / attendance stats for a specific event.
     */
    public function conversion(Request $request)
    {
        $request->validate([
            'event_id' => 'required|integer|exists:events,id',
        ]);

        $stats = $this->analyticsService->getConversionStats((int) $request->get('event_id'));

        return $this->successResponse($stats);
    }
}
