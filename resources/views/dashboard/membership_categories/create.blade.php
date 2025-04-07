@extends('layouts.dash')

@section('content')
<div class="col-lg-6">
    <h1 class="h3 mb-3"><strong>Create Membership Category</strong></h1>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add Membership Category</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('membership-categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Membership Category Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                </div>
            
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            
                <button type="submit" class="btn btn-primary">Save Category</button>
            </form>
            
        </div>
    </div>
</div>

@endsection
