@extends('layouts.dash')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Edit Event</h4>
                        <a href="#" class="btn btn-secondary">Back to Events</a>
                    </div>

                    <div class="card-body">
                        @if(!$event->event)
                            <div class="alert alert-warning">
                                This blog post doesn't have associated event details. Please create event details first.
                            </div>
                        @endif
                        
                        <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" id="eventForm">
                            @csrf
                            @method('PUT')

                            <!-- Blog Details Section -->
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2">Event Details</h5>
                                
                                <div class="mb-3">
                                    <label for="title" class="form-label">Event Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $event->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label">
                                        Event Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea 
                                        class="form-control @error('content') is-invalid @enderror" 
                                        id="content" 
                                        name="content" 
                                        rows="6" 
                                        required 
                                        aria-describedby="contentHelp"
                                    >{!! html_entity_decode(old('content', $event->content)) !!}</textarea>
                                    
                                    <div id="contentHelp" class="form-text">
                                        Please provide a detailed description of the event.
                                    </div>
                                
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="branch_id" class="form-label">Branch</label>
                                            <select class="form-control @error('branch_id') is-invalid @enderror" 
                                                    id="branch_id" name="branch_id">
                                                <option value="">Select Branch</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}" 
                                                            {{ old('branch_id', $event->branch_id) == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('branch_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Publication Status</label>
                                            <select class="form-control @error('status') is-invalid @enderror" 
                                                    id="status" name="status">
                                                <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Event Main Image</label>
                                    @if($event->image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $event->image) }}" alt="Current image" class="img-thumbnail" style="max-width: 200px;">
                                            <p class="text-muted">Current image</p>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Event Specific Details Section -->
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2">Event Information</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('event.start_date') is-invalid @enderror" 
                                                   id="start_date" name="event[start_date]" 
                                                   value="{{ old('event.start_date', $event->event->start_date ? $event->event->start_date->format('Y-m-d') : '') }}" required>
                                            @error('event.start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date" class="form-control @error('event.end_date') is-invalid @enderror" 
                                                   id="end_date" name="event[end_date]" 
                                                   value="{{ old('event.end_date', $event->event->end_date ? $event->event->end_date->format('Y-m-d') : '') }}">
                                            @error('event.end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control @error('event.start_time') is-invalid @enderror" 
                                                   id="start_time" name="event[start_time]" 
                                                   value="{{ old('event.start_time', $event->event->start_time ? \Carbon\Carbon::parse($event->event->start_time)->format('H:i') : '') }}" required>
                                            @error('event.start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_time" class="form-label">End Time</label>
                                            <input type="time" class="form-control @error('event.end_time') is-invalid @enderror" 
                                                   id="end_time" name="event[end_time]" 
                                                   value="{{ old('event.end_time', $event->event->end_time ? \Carbon\Carbon::parse($event->event->end_time)->format('H:i') : '') }}">
                                            @error('event.end_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="event_status" class="form-label">Event Status</label>
                                            <select class="form-control @error('event.status') is-invalid @enderror" 
                                                    id="event_status" name="event[status]">
                                                <option value="draft" {{ old('event.status', $event->event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="upcoming" {{ old('event.status', $event->event->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                                <option value="ongoing" {{ old('event.status', $event->event->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                <option value="completed" {{ old('event.status', $event->event->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ old('event.status', $event->event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            @error('event.status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Event Type</label>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="is_virtual" 
                                                       name="event[is_virtual]" value="1"
                                                       {{ old('event.is_virtual', $event->event->is_virtual) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_virtual">
                                                    Virtual Event
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Virtual Event Details -->
                                <div id="virtualEventDetails" style="{{ old('event.is_virtual', $event->event->is_virtual) ? '' : 'display: none;' }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="virtual_platform" class="form-label">Virtual Platform</label>
                                                <input type="text" class="form-control @error('event.virtual_platform') is-invalid @enderror" 
                                                       id="virtual_platform" name="event[virtual_platform]" 
                                                       value="{{ old('event.virtual_platform', $event->event->virtual_platform) }}"
                                                       placeholder="e.g., Zoom, Google Meet, Teams">
                                                @error('event.virtual_platform')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="virtual_link" class="form-label">Virtual Event Link</label>
                                                <input type="url" class="form-control @error('event.virtual_link') is-invalid @enderror" 
                                                       id="virtual_link" name="event[virtual_link]" 
                                                       value="{{ old('event.virtual_link', $event->event->virtual_link) }}"
                                                       placeholder="https://...">
                                                @error('event.virtual_link')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Physical Event Details -->
                                <div id="physicalEventDetails" style="{{ old('event.is_virtual', $event->event->is_virtual) ? 'display: none;' : '' }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="venue_name" class="form-label">Venue Name</label>
                                                <input type="text" class="form-control @error('event.venue_name') is-invalid @enderror" 
                                                       id="venue_name" name="event[venue_name]" 
                                                       value="{{ old('event.venue_name', $event->event->venue_name) }}">
                                                @error('event.venue_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address</label>
                                                <input type="text" class="form-control @error('event.address') is-invalid @enderror" 
                                                       id="address" name="event[address]" 
                                                       value="{{ old('event.address', $event->event->address) }}">
                                                @error('event.address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="city" class="form-label">City</label>
                                                <input type="text" class="form-control @error('event.city') is-invalid @enderror" 
                                                       id="city" name="event[city]" 
                                                       value="{{ old('event.city', $event->event->city) }}">
                                                @error('event.city')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="country" class="form-label">Country</label>
                                                <input type="text" class="form-control @error('event.country') is-invalid @enderror" 
                                                       id="country" name="event[country]" 
                                                       value="{{ old('event.country', $event->event->country) }}">
                                                @error('event.country')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Registration Details -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="registration_link" class="form-label">Registration Link</label>
                                            <input type="url" class="form-control @error('event.registration_link') is-invalid @enderror" 
                                                   id="registration_link" name="event[registration_link]" 
                                                   value="{{ old('event.registration_link', $event->event->registration_link) }}"
                                                   placeholder="https://...">
                                            @error('event.registration_link')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="max_attendees" class="form-label">Max Attendees</label>
                                            <input type="number" class="form-control @error('event.max_attendees') is-invalid @enderror" 
                                                   id="max_attendees" name="event[max_attendees]" 
                                                   value="{{ old('event.max_attendees', $event->event->max_attendees) }}"
                                                   min="0">
                                            @error('event.max_attendees')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Ticket Information -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="ticket_price" class="form-label">Ticket Price</label>
                                            <input type="number" class="form-control @error('event.ticket_price') is-invalid @enderror" 
                                                   id="ticket_price" name="event[ticket_price]" 
                                                   value="{{ old('event.ticket_price', $event->event->ticket_price) }}"
                                                   step="0.01" min="0">
                                            @error('event.ticket_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="ticket_currency" class="form-label">Currency</label>
                                            <select class="form-control @error('event.ticket_currency') is-invalid @enderror" 
                                                    id="ticket_currency" name="event[ticket_currency]">
                                                <option value="USD" {{ old('event.ticket_currency', $event->event->ticket_currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                                <option value="EUR" {{ old('event.ticket_currency', $event->event->ticket_currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                                <option value="GBP" {{ old('event.ticket_currency', $event->event->ticket_currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                                                <option value="UGX" {{ old('event.ticket_currency', $event->event->ticket_currency) == 'UGX' ? 'selected' : '' }}>UGX</option>
                                            </select>
                                            @error('event.ticket_currency')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="banner_image" class="form-label">Event Banner Image</label>
                                    @if($event->event->banner_image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $event->event->banner_image) }}" alt="Current banner" class="img-thumbnail" style="max-width: 200px;">
                                            <p class="text-muted">Current banner image</p>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('event.banner_image') is-invalid @enderror" 
                                           id="banner_image" name="event[banner_image]" accept="image/*">
                                    @error('event.banner_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Categories & Tags Section -->
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2">Categories & Tags</h5>
                                
                                <div class="mb-3">
                                    <label for="categories" class="form-label">Categories</label>
                                    <select class="form-control select2 @error('categories') is-invalid @enderror" 
                                            id="categories" name="categories[]" multiple>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ in_array($category->id, old('categories', $event->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categories')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tags</label>
                                    <select class="form-control select2-tags @error('tags') is-invalid @enderror" 
                                            id="tags" name="tags[]" multiple>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" 
                                                    {{ in_array($tag->id, old('tags', $event->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">Update Event</button>
                                <a href="#" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .form-label {
            font-weight: 600;
        }
        .text-danger {
            color: #dc3545;
        }
        .border-bottom {
            border-bottom: 2px solid #dee2e6;
        }
        .img-thumbnail {
            max-height: 150px;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Select2 for categories
            $('.select2').select2({
                placeholder: "Select categories",
                allowClear: true
            });

            // Initialize Select2 for tags with tagging enabled
            $('.select2-tags').select2({
                placeholder: "Select or create tags",
                tags: true,
                tokenSeparators: [',']
            });

            // Initialize CKEditor
            ClassicEditor
                .create(document.querySelector('#content'))
                .catch(error => {
                    console.error(error);
                });

            // Toggle virtual/physical event details
            $('#is_virtual').change(function() {
                if (this.checked) {
                    $('#virtualEventDetails').slideDown();
                    $('#physicalEventDetails').slideUp();
                } else {
                    $('#virtualEventDetails').slideUp();
                    $('#physicalEventDetails').slideDown();
                }
            });

            // Form validation
            $('#eventForm').on('submit', function(e) {
                let isValid = true;
                const requiredFields = ['title', 'content', 'start_date', 'start_time'];
                
                requiredFields.forEach(field => {
                    const input = $(`[name="${field}"], [name="event[${field}]"]`);
                    if (!input.val()) {
                        input.addClass('is-invalid');
                        isValid = false;
                    } else {
                        input.removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });

            // Date validation - end date should be after start date
            $('#end_date').change(function() {
                const startDate = $('#start_date').val();
                const endDate = $(this).val();
                
                if (startDate && endDate && endDate < startDate) {
                    alert('End date must be after start date');
                    $(this).val('');
                }
            });
        });
    </script>
@endsection