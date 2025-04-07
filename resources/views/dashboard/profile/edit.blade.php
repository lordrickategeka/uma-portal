@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Profile</h2>
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" value="{{ $profile->phone }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" class="form-control" required>{{ $profile->address }}</textarea>
        </div>

        <div class="form-group">
            <label for="uma_branch">UMA Branch</label>
            <input type="text" name="uma_branch" value="{{ $profile->uma_branch }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="employer">Employer</label>
            <input type="text" name="employer" value="{{ $profile->employer }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Update Profile</button>
    </form>
</div>
@endsection
