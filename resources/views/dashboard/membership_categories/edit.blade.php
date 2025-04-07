@extends('layouts.dash')

@section('content')
<h1 class="h3 mb-3"><strong>Edit Membership Category</strong></h1>

<div class="col-lg-6 col-md-6">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Membership Category</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('membership-categories.update', $membershipCategory->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Membership Category Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $membershipCategory->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control">{{ old('description', $membershipCategory->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', $membershipCategory->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $membershipCategory->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Category</button>
            </form>
        </div>
    </div>
</div>
@endsection
