<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Venue;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['venue', 'category'])->latest()->paginate(10);

        return Inertia::render('admin/events/index', [
            'events' => $events
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/events/create', [
            'venues' => Venue::all(),
            'categories' => EventCategory::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue_id' => 'required|exists:venues,id',
            'category_id' => 'required|exists:event_categories,id',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after:start_at',
            'status' => 'required|in:draft,published',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        $validated['organization_id'] = $request->user()->organizations()->first()->id;

        Event::create($validated);

        return redirect()->route('ticketflow.admin.events.index')
            ->with('message', 'Event created successfully.');
    }
}
