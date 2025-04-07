@extends('layouts.app')

@section('content')
<div class="container">
    <h2>User Profile</h2>
    <p><strong>Phone:</strong> {{ $profile->phone }}</p>
    <p><strong>Address:</strong> {{ $profile->address }}</p>
    <p><strong>UMA Branch:</strong> {{ $profile->uma_branch }}</p>
    <p><strong>Employer:</strong> {{ $profile->employer }}</p>
    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
</div>
@endsection
