<form action="{{ isset($blog) ? route('post.update', $blog) : route('post.store') }}" 
      method="POST" 
      enctype="multipart/form-data">
    @csrf
    @if (isset($blog))
        @method('PUT') <!-- This is necessary for the PUT request -->
    @endif

    <!-- Title Field -->
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" 
               name="title" 
               class="form-control" 
               value="{{ old('title', $blog->title ?? '') }}" 
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Post Type</label>
        <select name="post_type" class="form-control" required>
            <option value="post" {{ old('post_type', $blog->post_type ?? '') == 'post' ? 'selected' : '' }}>Post</option>
            <option value="event" {{ old('post_type', $blog->post_type ?? '') == 'event' ? 'selected' : '' }}>Event</option>
        </select>
    </div>

    <!-- Branch Field -->
    <div class="mb-3">
        <label class="form-label">Branch</label>
        <select name="branch_id" class="form-control" required>
            <option value="">-- Select Branch --</option>
            @foreach ($branches as $branch)
                <option value="{{ $branch->id }}"
                    {{ (isset($blog) && $blog->branch_id == $branch->id) || old('branch_id') == $branch->id ? 'selected' : '' }}>
                    {{ $branch->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Multiple Categories -->
    <div class="mb-3">
        <label class="form-label">Categories</label>
        <select name="category_ids[]" class="form-control" multiple required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" 
                        {{ (isset($blog) && in_array($category->id, $blog->categories->pluck('id')->toArray())) || old('category_ids') && in_array($category->id, old('category_ids')) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    
    
    <div class="mb-3">
        <label class="form-label">Tags</label>
        <input type="text" 
               name="tags" 
               class="form-control" 
               value="{{ old('tags', isset($blog) && $blog->tags ? implode(', ', $blog->tags->pluck('name')->toArray()) : '') }}" 
       placeholder="Enter tags separated by commas">
    </div>
    
    <!-- Content Field -->
    <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea id="editor" name="content" class="form-control">{{ old('content', $blog->content ?? '') }}</textarea>
    </div>

    <!-- Image Field -->
    <div class="mb-3">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control">
        @isset($blog->image)
            <p>Current Image: <img src="{{ asset('storage/' . $blog->image) }}" alt="Current Image" class="img-fluid" width="100"></p>
        @endisset
    </div>

    <!-- Status Field -->
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="draft" {{ (isset($blog) && $blog->status == 'draft') || old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ (isset($blog) && $blog->status == 'published') || old('status') == 'published' ? 'selected' : '' }}>Published</option>
            <option value="archived" {{ (isset($blog) && $blog->status == 'archived') || old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">
        {{ isset($blog) ? 'Update' : 'Save' }}
    </button>
</form>
