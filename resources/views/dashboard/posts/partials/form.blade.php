{{-- <form action="{{ isset($blog) ? route('post.update', $blog) : route('post.store') }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @if (isset($blog))
        @method('PUT')
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following issues:<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Title Field -->
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title ?? '') }}" required>
        @error('title')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Post Type</label>
        <select name="post_type" class="form-control" required>
            <option value="post" {{ old('post_type', $blog->post_type ?? '') == 'post' ? 'selected' : '' }}>Post
            </option>
            <option value="event" {{ old('post_type', $blog->post_type ?? '') == 'event' ? 'selected' : '' }}>Event
            </option>
        </select>
        @error('post_type')
            <div class="text-danger">{{ $message }}</div>
        @enderror
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
        @error('branch_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Multiple Categories -->
    <div class="mb-3">
        <label class="form-label">Categories</label>
        <select name="category_ids[]" class="form-control" multiple required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ (isset($blog) && in_array($category->id, $blog->categories->pluck('id')->toArray())) || (old('category_ids') && in_array($category->id, old('category_ids'))) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_ids')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>


    <div class="mb-3">
        <label class="form-label">Tags</label>
        <input type="text" name="tags" class="form-control"
            value="{{ old('tags', isset($blog) && $blog->tags ? implode(', ', $blog->tags->pluck('name')->toArray()) : '') }}"
            placeholder="Enter tags separated by commas">

    </div>

    <!-- Content Field -->
    <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea id="editor" name="content" rows="20" cols="20" class="form-control">{{ old('content', $blog->content ?? '') }}</textarea>
        @error('content')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Image Field -->
    <div class="mb-3">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control">
        @isset($blog->image)
            <p>Current Image: <img src="{{ asset('storage/' . $blog->image) }}" alt="Current Image" class="img-fluid"
                    width="100"></p>
        @endisset
    </div>

    <!-- date -->
    <div class="form-group">
        <label for="published_at">Publish Date (optional)</label>
        <input type="date" name="published_at" class="form-control"
            value="{{ old('published_at', isset($blog) && $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('Y-m-d\TH:i') : '') }}">
    </div>

    <!-- Status Field -->
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="draft"
                {{ (isset($blog) && $blog->status == 'draft') || old('status') == 'draft' ? 'selected' : '' }}>Draft
            </option>
            <option value="published"
                {{ (isset($blog) && $blog->status == 'published') || old('status') == 'published' ? 'selected' : '' }}>
                Published</option>
            <option value="archived"
                {{ (isset($blog) && $blog->status == 'archived') || old('status') == 'archived' ? 'selected' : '' }}>
                Archived</option>
        </select>
        @error('status')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-success">
        {{ isset($blog) ? 'Update' : 'Save' }}
    </button>
</form>
<script>
    document.querySelector('form').addEventListener('submit', function() {
        console.log('Form submitted!');
    });
</script> --}}

<div class="container mt-5">
    <div class="row">
        <!-- General Fields Card -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>General Post Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($blog) ? route('post.update', $blog) : route('post.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($blog))
                            @method('PUT')
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> Please fix the following issues:<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Title Field -->
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title ?? '') }}" required>
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Post Type Field -->
                        <div class="mb-3">
                            <label class="form-label">Post Type</label>
                            <select name="post_type" class="form-control" required>
                                <option value="post" {{ old('post_type', $blog->post_type ?? '') == 'post' ? 'selected' : '' }}>Post</option>
                                <option value="event" {{ old('post_type', $blog->post_type ?? '') == 'event' ? 'selected' : '' }}>Event</option>
                            </select>
                            @error('post_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
                            @error('branch_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Categories Field -->
                        <div class="mb-3">
                            <label class="form-label">Categories</label>
                            <select name="category_ids[]" class="form-control" multiple required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ (isset($blog) && in_array($category->id, $blog->categories->pluck('id')->toArray())) || (old('category_ids') && in_array($category->id, old('category_ids'))) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_ids')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tags Field -->
                        <div class="mb-3">
                            <label class="form-label">Tags</label>
                            <input type="text" name="tags" class="form-control"
                                value="{{ old('tags', isset($blog) && $blog->tags ? implode(', ', $blog->tags->pluck('name')->toArray()) : '') }}"
                                placeholder="Enter tags separated by commas">
                        </div>

                        <!-- Content Field -->
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea id="editor" name="content" rows="10" cols="20" class="form-control">{{ old('content', $blog->content ?? '') }}</textarea>
                            @error('content')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image Field -->
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control">
                            @isset($blog->image)
                                <p>Current Image: <img src="{{ asset('storage/' . $blog->image) }}" alt="Current Image" class="img-fluid" width="100"></p>
                            @endisset
                        </div>

                        <!-- Publish Date -->
                        <div class="form-group mb-3">
                            <label for="published_at">Publish Date (optional)</label>
                            <input type="date" name="published_at" class="form-control"
                                value="{{ old('published_at', isset($blog) && $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('Y-m-d\TH:i') : '') }}">
                        </div>

                        <!-- Status Field -->
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="draft"
                                    {{ (isset($blog) && $blog->status == 'draft') || old('status') == 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="published"
                                    {{ (isset($blog) && $blog->status == 'published') || old('status') == 'published' ? 'selected' : '' }}>
                                    Published
                                </option>
                                <option value="archived"
                                    {{ (isset($blog) && $blog->status == 'archived') || old('status') == 'archived' ? 'selected' : '' }}>
                                    Archived
                                </option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">
                            {{ isset($blog) ? 'Update' : 'Save' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Event Fields Card -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Event Information</h5>
                </div>
                <div class="card-body">
                    <!-- Event-specific Fields (only visible when post type is 'event') -->
                    <div class="event-specific-fields" style="display: none;">
                        <!-- Start Date -->
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $blog->start_date ?? '') }}" required>
                        </div>

                        <!-- End Date -->
                        <div class="mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $blog->end_date ?? '') }}" required>
                        </div>

                        <!-- Start Time -->
                        <div class="mb-3">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="start_time" class="form-control" value="{{ old('start_time', $blog->start_time ?? '') }}" required>
                        </div>

                        <!-- End Time -->
                        <div class="mb-3">
                            <label class="form-label">End Time</label>
                            <input type="time" name="end_time" class="form-control" value="{{ old('end_time', $blog->end_time ?? '') }}" required>
                        </div>

                        <!-- Venue Name -->
                        <div class="mb-3">
                            <label class="form-label">Venue Name</label>
                            <input type="text" name="venue_name" class="form-control" value="{{ old('venue_name', $blog->venue_name ?? '') }}" required>
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $blog->address ?? '') }}" required>
                        </div>

                        <!-- City -->
                        <div class="mb-3">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $blog->city ?? '') }}" required>
                        </div>

                        <!-- Country -->
                        <div class="mb-3">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control" value="{{ old('country', $blog->country ?? '') }}" required>
                        </div>

                        <!-- Virtual Event Option -->
                        <div class="mb-3">
                            <label class="form-label">Is Virtual Event?</label>
                            <input type="checkbox" name="is_virtual" class="form-check-input" value="1" {{ old('is_virtual', $blog->is_virtual ?? '') == 1 ? 'checked' : '' }}>
                        </div>

                        <!-- Virtual Platform -->
                        <div class="mb-3">
                            <label class="form-label">Virtual Platform</label>
                            <input type="text" name="virtual_platform" class="form-control" value="{{ old('virtual_platform', $blog->virtual_platform ?? '') }}">
                        </div>

                        <!-- Virtual Link -->
                        <div class="mb-3">
                            <label class="form-label">Virtual Event Link</label>
                            <input type="url" name="virtual_link" class="form-control" value="{{ old('virtual_link', $blog->virtual_link ?? '') }}">
                        </div>

                        <!-- Registration Link -->
                        <div class="mb-3">
                            <label class="form-label">Registration Link</label>
                            <input type="url" name="registration_link" class="form-control" value="{{ old('registration_link', $blog->registration_link ?? '') }}">
                        </div>

                        <!-- Max Attendees -->
                        <div class="mb-3">
                            <label class="form-label">Max Attendees</label>
                            <input type="number" name="max_attendees" class="form-control" value="{{ old('max_attendees', $blog->max_attendees ?? '') }}">
                        </div>

                        <!-- Ticket Price -->
                        <div class="mb-3">
                            <label class="form-label">Ticket Price</label>
                            <input type="number" name="ticket_price" class="form-control" value="{{ old('ticket_price', $blog->ticket_price ?? '') }}">
                        </div>

                        <!-- Ticket Currency -->
                        <div class="mb-3">
                            <label class="form-label">Ticket Currency</label>
                            <input type="text" name="ticket_currency" class="form-control" value="{{ old('ticket_currency', $blog->ticket_currency ?? '') }}">
                        </div>

                        <!-- Banner Image -->
                        <div class="mb-3">
                            <label class="form-label">Banner Image</label>
                            <input type="file" name="banner_image" class="form-control">
                            @isset($blog->banner_image)
                                <p>Current Banner Image: <img src="{{ asset('storage/' . $blog->banner_image) }}" alt="Current Banner Image" class="img-fluid" width="100"></p>
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelector('select[name="post_type"]').addEventListener('change', function () {
        const postType = this.value;
        const eventFields = document.querySelector('.event-specific-fields');
        if (postType === 'event') {
            eventFields.style.display = 'block';
        } else {
            eventFields.style.display = 'none';
        }
    });

    // Trigger change event on load to handle any default values
    document.querySelector('select[name="post_type"]').dispatchEvent(new Event('change'));
</script>




