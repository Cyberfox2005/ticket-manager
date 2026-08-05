<?php

namespace App\Modules\Events\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Events\Services\EventService;
use App\Modules\Shared\Infrastructure\ApiResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use ApiResponse;

    public function __construct(protected EventService $eventService) {}

    public function index(Request $request)
    {
        $events = $this->eventService->getList($request->all());
        return $this->successResponse($events);
    }

    public function show(int $id)
    {
        $event = $this->eventService->getDetails($id);

        if (! $event) {
            return $this->errorResponse('Event not found', 404);
        }

        return $this->successResponse($event);
    }
}
