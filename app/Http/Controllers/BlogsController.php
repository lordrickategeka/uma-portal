<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Event;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogsController extends Controller
{
    // guest side of web
    public function index()
    {
        $blogs = Blog::with(['author', 'categories', 'tags', 'branch'])
            ->where('status', 'published')
            ->where('post_type', 'Post')
            ->latest()
            ->paginate(6);

        return view('web.blog.all_blogs', compact('blogs'));  // Pass the blogs data to the view
    }

    // dashbord
    public function allPosts()
    {
        $blogs = Blog::with(['categories', 'tags', 'branch'])
        ->where('post_type', 'Post')
        ->latest()->paginate(10);
        return view('dashboard.posts.all_posts', compact('blogs'));
    }

    public function create()
    {
        $categories = Category::all();
        $branches = Branch::all();
        return view('dashboard.posts.create', compact('categories', 'branches'));
    }


    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'category_ids' => 'required|array', // Ensure it's an array
            'status' => 'required|in:draft,published,archived',
            'tags' => 'nullable|string', // Comma-separated tags
            'branch_id' => 'required|exists:branches,id',
            'post_type' => 'required|in:post,event',
            'published_at' => 'nullable|date',
            // Event-specific fields
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'venue_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'is_virtual' => 'nullable|boolean',
            'virtual_platform' => 'nullable|string',
            'virtual_link' => 'nullable|url',
            'registration_link' => 'nullable|url',
            'max_attendees' => 'nullable|integer',
            'ticket_price' => 'nullable|numeric',
            'ticket_currency' => 'nullable|string|max:5',
            'banner_image' => 'nullable|image|max:10240', // Optional Image Upload for Event Banner
        ]);

        // Prepare the data for blog creation
        $data = $request->all();
        $data['author_id'] = Auth::user()->id;
        $data['slug'] = Str::slug($request->title);

        // Handle published date based on status
        if ($request->status === 'published') {
            $data['published_at'] = $request->filled('published_at')
                ? Carbon::parse($request->published_at)->format('Y-m-d') // Date only
                : now()->format('Y-m-d');
        } else {
            $data['published_at'] = null;
        }

        // Handle image upload if exists
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news_images', 'public');
        }

        // Create the blog post
        $blog = Blog::create($data);

        // Attach categories (many-to-many relationship)
        $blog->categories()->sync($request->category_ids); // Syncing multiple categories

        // Save tags (if any)
        if ($request->tags) {
            $tags = explode(',', $request->tags);  // Split the tags by comma
            $tags = array_map('trim', $tags); // Remove extra spaces

            // For each tag, find or create it, then attach to the blog
            foreach ($tags as $tag) {
                $tag = Tag::firstOrCreate(['name' => $tag]);  // Create the tag if it doesn't exist
                $blog->tags()->attach($tag);  // Attach the tag to the blog
            }
        }

        // If the post is an event, create the event record
        if ($request->post_type === 'event') {
            $eventData = $request->only([
                'start_date',
                'end_date',
                'start_time',
                'end_time',
                'venue_name',
                'address',
                'city',
                'country',
                'is_virtual',
                'virtual_platform',
                'virtual_link',
                'registration_link',
                'max_attendees',
                'ticket_price',
                'ticket_currency'
            ]);

            // Handle banner image for event if exists
            if ($request->hasFile('banner_image')) {
                $eventData['banner_image'] = $request->file('banner_image')->store('events', 'public');
            }

            // Create the event and associate with the blog
            $event = new Event($eventData);
            $event->blog_id = $blog->id; // Associate the event with the post
            $event->save();
        }

        return redirect()->route('posts.all')->with('success', 'Post created successfully!');
    }

    public function show(string $id, string $slug)
    {
        $blog = Blog::with('author', 'categories', 'tags')->findOrFail($id);

        if ($slug !== Str::slug($blog->title)) {
            return redirect()->route('blog.show', ['id' => $id, 'slug' => Str::slug($blog->title)]);
        }

        $latestBlogs = Blog::latest()->take(5)->get();

        return view('web.blog.single_blog', compact('blog', 'latestBlogs'));
    }


    public function edit(Blog $blog)
    {
        $categories = Category::all();
        $branches = Branch::all();
        return view('dashboard.posts.edit', compact('blog', 'categories', 'branches'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'category_ids' => 'required|array', // Ensure it's an array
            'status' => 'required|in:draft,published,archived',
            'tags' => 'nullable|string', // Comma-separated tags
            'branch_id' => 'required|exists:branches,id',
            'post_type' => 'required|in:post,event',
            'published_at' => 'nullable|date',
        ]);

        // Prepare the data for blog update
        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        if ($request->status === 'published') {
            $data['published_at'] = $request->filled('published_at')
                ? Carbon::parse($request->published_at)
                : now();
        } else {
            $data['published_at'] = null;
        }

        // Handle image upload if exists
        if ($request->hasFile('image')) {
            // Delete old image if exists (optional)
            if ($blog->image && file_exists(storage_path('app/public/' . $blog->image))) {
                unlink(storage_path('app/public/' . $blog->image));
            }

            $data['image'] = $request->file('image')->store('news_images', 'public');
        }

        // Update the blog post
        $blog->update($data);

        // Sync categories (many-to-many relationship)
        $blog->categories()->sync($request->category_ids);

        // Update tags (if any)
        if ($request->tags) {
            $tags = explode(',', $request->tags);  // Split the tags by comma
            $tags = array_map('trim', $tags); // Remove extra spaces

            // Sync the tags for the blog (this will remove old tags and add new ones)
            $tagIds = [];
            foreach ($tags as $tag) {
                $tagInstance = Tag::firstOrCreate(['name' => $tag]);  // Create or find the tag
                $tagIds[] = $tagInstance->id;  // Collect tag IDs
            }

            // Sync the tags for this blog post
            $blog->tags()->sync($tagIds);
        }

        return redirect()->route('posts.all')->with('success', 'News article updated successfully!');
    }


    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->back()->with('success', 'Article deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected_ids', []);

        if (empty($ids)) {
            return back()->with('error', 'No articles selected for deletion.');
        }

        // Optionally delete images associated with articles
        $blogs = Blog::whereIn('id', $ids)->get();
        foreach ($blogs as $blog) {
            if ($blog->image && file_exists(storage_path('app/public/' . $blog->image))) {
                unlink(storage_path('app/public/' . $blog->image));
            }
            $blog->categories()->detach();
            $blog->tags()->detach();
            $blog->delete();
        }

        return back()->with('success', 'Selected articles deleted successfully.');
    }
}
