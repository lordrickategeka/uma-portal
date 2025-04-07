@extends('layouts.dash')
@section('content')
<div class="container">
    <h2>Branch Details</h2>
    <ul class="list-group">
        <li class="list-group-item"><strong>Name:</strong> {{ $branch->name }}</li>
        <li class="list-group-item"><strong>Code:</strong> {{ $branch->code }}</li>
        <li class="list-group-item"><strong>Location:</strong> {{ $branch->location }}</li>
        <li class="list-group-item"><strong>Address:</strong> {{ $branch->address }}</li>
        <li class="list-group-item"><strong>Email:</strong> {{ $branch->email }}</li>
        <li class="list-group-item"><strong>Phone:</strong> {{ $branch->phone }}</li>
        <li class="list-group-item"><strong>Manager:</strong> {{ $branch->manager }}</li>
        <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($branch->status) }}</li>
    </ul>
    <a href="{{ route('branches.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection