<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Tag;
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
        $blogs = Blog::with(['categories', 'tags', 'branch'])->latest()->paginate(10);
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'category_ids' => 'required|array', // Ensure it's an array
            'status' => 'required|in:draft,published,archived',
            'tags' => 'nullable|string', // Comma-separated tags
            'branch_id' => 'required|exists:branches,id',
            'post_type' => 'required|in:post,event',
        ]);

        // Prepare the data for blog creation
        $data = $request->all();
        $data['author_id'] = Auth::user()->id;
        $data['slug'] = Str::slug($request->title);
        $data['published_at'] = $request->status === 'published' ? now() : null;

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

        return redirect()->route('posts.all')->with('success', 'News article created successfully!');
    }


    public function show(string $id)
    {
        $blog = Blog::with('author', 'categories', 'tags')->findOrFail($id);
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
        ]);

        // Prepare the data for blog update
        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['published_at'] = $request->status === 'published' ? now() : null;

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
}
