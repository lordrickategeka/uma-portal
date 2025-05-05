<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('dashboard.events.all_events', compact('events'));
    }

    // web-page-side
    public function eventsPage()
    {
        $events = Event::with(['author', 'categories', 'tags', 'branch'])
            ->where('status', 'published')
            ->where('post_type', 'Event')
            ->latest()
            ->paginate(6);

        return view('web.event.all_events', compact('events'));
    }

    public function create()
    {
        return view('dashboard.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'venue_name' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'location_url' => 'nullable|url',
            'is_virtual' => 'boolean',
            'virtual_platform' => 'nullable|string',
            'virtual_link' => 'nullable|url',
            'is_registration_required' => 'boolean',
            'registration_link' => 'nullable|url',
            'ticket_price' => 'nullable|numeric',
            'ticket_currency' => 'nullable|string|max:10',
            'banner_image' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['created_by'] = Auth::id();

        Event::create($data);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'venue_name' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'location_url' => 'nullable|url',
            'is_virtual' => 'boolean',
            'virtual_platform' => 'nullable|string',
            'virtual_link' => 'nullable|url',
            'is_registration_required' => 'boolean',
            'registration_link' => 'nullable|url',
            'ticket_price' => 'nullable|numeric',
            'ticket_currency' => 'nullable|string|max:10',
            'banner_image' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $event->update($data);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted.');
    }
    public function publish(Event $event)
    {
        // Only admin or editor can publish
        if (!Auth::user()->can('publish', $event)) {
            return redirect()->route('events.index')->with('error', 'You do not have permission to publish this event.');
        }

        $event->status = 'published';
        $event->published_at = now();
        $event->save();

        return redirect()->route('events.index')->with('success', 'Event published successfully.');
    }
}
