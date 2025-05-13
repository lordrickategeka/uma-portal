<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Event;
use App\Models\Tag;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    // dashboard
    public function allEvents()
    {
        $events = Blog::with(['categories', 'tags', 'branch'])
            ->where('post_type', 'event')
            ->latest()->paginate(10);
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
        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string', // Blog content field
            'summary' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Main blog image
            'category_ids' => 'required|array', // Blog categories
            'status' => 'required|in:draft,published,archived',
            'tags' => 'nullable|string', // Comma-separated tags
            'branch_id' => 'required|exists:branches,id',

            // Event-specific fields
            'start_date' => 'required|date', // Make required for events
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'required', // Make required for events
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
            'max_attendees' => 'nullable|integer|min:1',
            'ticket_price' => 'nullable|numeric',
            'ticket_currency' => 'nullable|string|max:5',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Event banner image
            'event_status' => 'nullable|in:draft,upcoming,ongoing,completed,cancelled',
        ]);

        // Start database transaction
        DB::beginTransaction();

        try {
            // First, create the blog post
            $blog = new Blog();
            $blog->title = $validated['title'];
            $blog->content = $validated['content'];
            $blog->status = $validated['status'];
            $blog->branch_id = $validated['branch_id'];
            $blog->author_id = Auth::id();
            $blog->post_type = 'event'; // Mark this blog as an event
            $blog->slug = Str::slug($validated['title']);

            // Handle published date based on status
            if ($validated['status'] === 'published') {
                $blog->published_at = now();
            }

            // Handle main blog image upload if exists
            if ($request->hasFile('image')) {
                // Generate a unique filename
                $filename = time() . '_' . $request->file('image')->getClientOriginalName();

                // Store the file using the 'news_images' disk
                $path = Storage::disk('news_images')->putFileAs('', $request->file('image'), $filename);

                // Save the relative path
                $blog->image = $path;
            }

            // Save the blog post
            $blog->save();

            // Now create the event record
            $event = new Event();
            $event->blog_id = $blog->id; // Link to the blog post

            // Copy validated event fields
            $event->start_date = $validated['start_date'];
            $event->end_date = $validated['end_date'] ?? null;
            $event->start_time = $validated['start_time'];
            $event->end_time = $validated['end_time'] ?? null;
            $event->venue_name = $validated['venue_name'] ?? null;
            $event->address = $validated['address'] ?? null;
            $event->city = $validated['city'] ?? null;
            $event->country = $validated['country'] ?? null;
            $event->location_url = $validated['location_url'] ?? null;
            $event->is_virtual = $request->has('is_virtual');
            $event->virtual_platform = $validated['virtual_platform'] ?? null;
            $event->virtual_link = $validated['virtual_link'] ?? null;
            $event->is_registration_required = $request->has('is_registration_required');
            $event->registration_link = $validated['registration_link'] ?? null;
            $event->max_attendees = $validated['max_attendees'] ?? null;
            $event->ticket_price = $validated['ticket_price'] ?? null;
            $event->ticket_currency = $validated['ticket_currency'] ?? null;
            $event->status = $validated['event_status'] ?? 'draft';
            $event->summary = $validated['summary'] ?? null;

            // Handle banner image upload if exists
            if ($request->hasFile('banner_image')) {
                // Generate a unique filename
                $filename = time() . '_' . $request->file('banner_image')->getClientOriginalName();

                // Store the file using the 'events' disk
                $path = Storage::disk('events')->putFileAs('', $request->file('banner_image'), $filename);

                // Save the relative path
                $event->banner_image = $path;
            }

            // Save the event
            $event->save();

            // Attach categories (many-to-many relationship)
            if (isset($validated['category_ids'])) {
                $blog->categories()->sync($validated['category_ids']);
            }

            // Handle tags
            if (!empty($validated['tags'])) {
                $tags = explode(',', $validated['tags']);  // Split by comma
                $tags = array_map('trim', $tags); // Remove extra spaces

                $tagIds = [];
                foreach ($tags as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }

                // Sync tags
                $blog->tags()->sync($tagIds);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('events.index')->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            // Roll back the transaction on error
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating event: ' . $e->getMessage());
        }
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function edit($id)
    {
        $event = Blog::with(['event', 'categories', 'tags'])
            ->where('post_type', 'event')
            ->findOrFail($id);

        $branches = Branch::all();
        $categories = Category::all();
        $tags = Tag::all();

        return view('dashboard.events.edit', compact('event', 'branches', 'categories', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $event = Blog::with('event')->where('post_type', 'event')->findOrFail($id);

        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'branch_id' => 'nullable|exists:branches,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Event specific fields
            'event.start_date' => 'required|date',
            'event.end_date' => 'nullable|date|after_or_equal:event.start_date',
            'event.start_time' => 'required',
            'event.end_time' => 'nullable',
            'event.venue_name' => 'nullable|string|max:255',
            'event.address' => 'nullable|string|max:255',
            'event.city' => 'nullable|string|max:255',
            'event.country' => 'nullable|string|max:255',
            'event.is_virtual' => 'boolean',
            'event.virtual_platform' => 'nullable|string|max:255',
            'event.virtual_link' => 'nullable|url',
            'event.registration_link' => 'nullable|url',
            'event.max_attendees' => 'nullable|integer|min:1',
            'event.ticket_price' => 'nullable|numeric|min:0',
            'event.ticket_currency' => 'nullable|string|max:3',
            'event.banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event.status' => 'nullable|in:draft,upcoming,ongoing,completed,cancelled',

            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // Update blog details
            $event->title = $validated['title'];
            $event->content = $validated['content'];
            $event->status = $validated['status'];
            $event->branch_id = $validated['branch_id'] ?? null;
            $event->slug = Str::slug($validated['title']);

            // Handle main image upload
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($event->image && Storage::disk('news_images')->exists($event->image)) {
                    Storage::disk('news_images')->delete($event->image);
                }

                // Generate a unique filename
                $filename = time() . '_' . $request->file('image')->getClientOriginalName();

                // Store the file using the 'news_images' disk
                $path = Storage::disk('news_images')->putFileAs('', $request->file('image'), $filename);

                // Update the path
                $event->image = $path;
            }

            // Set published_at if publishing for the first time
            if ($validated['status'] === 'published' && $event->published_at === null) {
                $event->published_at = now();
            }

            $event->save();

            // Update event details
            $eventData = $validated['event'];

            // Handle banner image upload
            if ($request->hasFile('event.banner_image')) {
                // Delete old banner image if it exists
                if ($event->event->banner_image && Storage::disk('events')->exists($event->event->banner_image)) {
                    Storage::disk('events')->delete($event->event->banner_image);
                }

                // Generate a unique filename
                $filename = time() . '_' . $request->file('event.banner_image')->getClientOriginalName();

                // Store the file using the 'events' disk
                $path = Storage::disk('events')->putFileAs('', $request->file('event.banner_image'), $filename);

                // Update the path
                $eventData['banner_image'] = $path;
            }

            // Handle is_virtual checkbox
            $eventData['is_virtual'] = $request->has('event.is_virtual');

            // Update the associated event
            $event->event->update($eventData);

            // Sync categories
            if (isset($validated['categories'])) {
                $event->categories()->sync($validated['categories']);
            }

            // Sync tags
            if (isset($validated['tags'])) {
                // Handle new tags
                $tagIds = [];
                foreach ($validated['tags'] as $tagId) {
                    if (!is_numeric($tagId)) {
                        // Create new tag
                        $newTag = Tag::create(['name' => $tagId]);
                        $tagIds[] = $newTag->id;
                    } else {
                        $tagIds[] = $tagId;
                    }
                }
                $event->tags()->sync($tagIds);
            }

            DB::commit();

            return redirect()->route('events.allEvents')
                ->with('success', 'Event updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating event: ' . $e->getMessage());
        }
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
    // ==================
    // web view side
    // ==================


    /**
     * Display a listing of all event posts.
     */
    public function webAllEvents(Request $request)
    {
        $query = Blog::events()
            ->with(['event', 'author', 'categories', 'tags', 'branch']);

        // Filter by status - SPECIFY THE TABLE
        if ($request->filled('status')) {
            $query->where('blogs.status', $request->status);
        }

        // Filter published posts
        if ($request->boolean('published', true)) {
            $query->published();
        }

        // Filter by event status
        if ($request->filled('event_status')) {
            $query->whereHas('event', function ($q) use ($request) {
                $q->where('status', $request->event_status);
            });
        }

        // Upcoming events
        if ($request->boolean('upcoming')) {
            $query->whereHas('event', function ($q) {
                $q->upcoming();
            });
        }

        // Past events
        if ($request->boolean('past')) {
            $query->whereHas('event', function ($q) {
                $q->past();
            });
        }

        // Virtual events
        if ($request->has('is_virtual')) {
            $query->whereHas('event', function ($q) use ($request) {
                $q->where('is_virtual', $request->boolean('is_virtual'));
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'event_date');
        $sortOrder = $request->get('sort_order', 'asc');

        if ($sortBy === 'event_date') {
            // Sort by event start date
            $query->join('events', 'blogs.id', '=', 'events.blog_id')
                ->orderBy('events.start_date', $sortOrder)
                ->orderBy('events.start_time', $sortOrder)
                ->select('blogs.*');
        } else {
            // Sort by blog fields
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $eventPosts = $request->filled('per_page')
            ? $query->paginate($request->per_page)
            : $query->get();

        return view('web.event.all_events', compact('eventPosts'));
    }

    /**
     * Get all posts with type event (API endpoint)
     */
    public function getAllPostsWithTypeEvent(Request $request)
    {
        $query = Blog::events()
            ->with(['event' => function ($q) {
                $q->select('*')
                    ->selectRaw('CASE 
                      WHEN start_date > CURDATE() THEN "upcoming"
                      WHEN start_date = CURDATE() AND start_time > CURTIME() THEN "upcoming"
                      WHEN end_date < CURDATE() THEN "past"
                      WHEN end_date = CURDATE() AND end_time < CURTIME() THEN "past"
                      ELSE "ongoing"
                  END as event_timing');
            }, 'author', 'categories', 'tags']);

        // Default to published only
        if (!$request->has('include_drafts')) {
            $query->published();
        }

        // Ensure posts have associated events
        $query->whereHas('event');

        // Apply filters
        $this->applyEventFilters($query, $request);

        // Get results
        $eventPosts = $query->get();

        // Return JSON for API or view for web
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $eventPosts,
                'count' => $eventPosts->count()
            ]);
        }

        return view('events.all', compact('eventPosts'));
    }

    /**
     * Get upcoming events
     */
    public function upcoming(Request $request)
    {
        $eventPosts = Blog::events()
            ->published()
            ->with(['event', 'author', 'categories', 'tags'])
            ->whereHas('event', function ($q) {
                $q->upcoming();
            })
            ->join('events', 'blogs.id', '=', 'events.blog_id')
            ->orderBy('events.start_date', 'asc')
            ->orderBy('events.start_time', 'asc')
            ->select('blogs.*')
            ->paginate($request->get('per_page', 12));

        return view('events.upcoming', compact('eventPosts'));
    }

    /**
     * Get past events
     */
    public function past(Request $request)
    {
        $eventPosts = Blog::events()
            ->published()
            ->with(['event', 'author', 'categories', 'tags'])
            ->whereHas('event', function ($q) {
                $q->past();
            })
            ->join('events', 'blogs.id', '=', 'events.blog_id')
            ->orderBy('events.start_date', 'desc')
            ->orderBy('events.start_time', 'desc')
            ->select('blogs.*')
            ->paginate($request->get('per_page', 12));

        return view('events.past', compact('eventPosts'));
    }

    /**
     * Show a single event
     */
    public function showEvent($slug)
    {
        $eventPost = Blog::events()
            ->published()
            ->where('slug', $slug)
            ->with(['event', 'author', 'categories', 'tags'])
            ->firstOrFail();

        // Increment views
        $eventPost->increment('views');

        // Get related events
        $relatedEvents = $this->getRelatedEvents($eventPost);

        return view('events.show', compact('eventPost', 'relatedEvents'));
    }

    /**
     * Apply event filters to query
     */
    protected function applyEventFilters($query, Request $request)
    {
        // Event status filter
        if ($request->filled('event_status')) {
            $query->whereHas('event', function ($q) use ($request) {
                $q->where('status', $request->event_status);
            });
        }

        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereHas('event', function ($q) use ($request) {
                $q->where('start_date', '>=', $request->start_date);
            });
        }

        if ($request->filled('end_date')) {
            $query->whereHas('event', function ($q) use ($request) {
                $q->where('end_date', '<=', $request->end_date);
            });
        }

        // Location filter
        if ($request->filled('city')) {
            $query->whereHas('event', function ($q) use ($request) {
                $q->where('city', 'like', '%' . $request->city . '%');
            });
        }

        // Virtual/Physical filter
        if ($request->has('event_type')) {
            $isVirtual = $request->event_type === 'virtual';
            $query->whereHas('event', function ($q) use ($isVirtual) {
                $q->where('is_virtual', $isVirtual);
            });
        }

        // Price filter
        if ($request->has('free_only') && $request->boolean('free_only')) {
            $query->whereHas('event', function ($q) {
                $q->whereNull('ticket_price')
                    ->orWhere('ticket_price', 0);
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category)
                    ->orWhere('categories.slug', $request->category);
            });
        }

        // Tag filter
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag)
                    ->orWhere('tags.slug', $request->tag);
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%')
                    ->orWhereHas('event', function ($eq) use ($search) {
                        $eq->where('venue_name', 'like', '%' . $search . '%')
                            ->orWhere('city', 'like', '%' . $search . '%');
                    });
            });
        }
    }

    /**
     * Get related events
     */
    protected function getRelatedEvents($eventPost, $limit = 3)
    {
        $categoryIds = $eventPost->categories->pluck('id');
        $tagIds = $eventPost->tags->pluck('id');

        return Blog::events()
            ->published()
            ->with(['event', 'author'])
            ->where('id', '!=', $eventPost->id)
            ->where(function ($q) use ($categoryIds, $tagIds) {
                $q->whereHas('categories', function ($cq) use ($categoryIds) {
                    $cq->whereIn('categories.id', $categoryIds);
                })
                    ->orWhereHas('tags', function ($tq) use ($tagIds) {
                        $tq->whereIn('tags.id', $tagIds);
                    });
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Get events calendar data
     */
    public function calendar(Request $request)
    {
        $start = $request->get('start', now()->startOfMonth());
        $end = $request->get('end', now()->endOfMonth());

        $events = Blog::events()
            ->published()
            ->with(['event'])
            ->whereHas('event', function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end]);
            })
            ->get()
            ->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'start' => $blog->event->start_date->format('Y-m-d') . 'T' . $blog->event->start_time,
                    'end' => $blog->event->end_date->format('Y-m-d') . 'T' . $blog->event->end_time,
                    'url' => route('events.show', $blog->slug),
                    'color' => $blog->event->is_virtual ? '#3B82F6' : '#10B981',
                    'extendedProps' => [
                        'venue' => $blog->event->venue_name,
                        'location' => $blog->event->full_location,
                        'price' => $blog->event->formatted_price
                    ]
                ];
            });

        return response()->json($events);
    }
}
