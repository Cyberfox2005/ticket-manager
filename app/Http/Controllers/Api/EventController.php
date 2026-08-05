<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::with(['venue', 'category', 'ticketTypes'])
            ->where('status', 'published')
            ->when($request->category, function ($query, $category) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $category));
            })
            ->latest()
            ->paginate(20);

        return response()->json($events);
    }

    public function show(Event $event)
    {
        $event->load(['venue', 'category', 'ticketTypes']);

        return response()->json($event);
    }
}
