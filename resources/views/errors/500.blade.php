@extends('layouts.web-pages')

@section('content')
    <div class="container text-center">
        <h1 class="text-danger">500 - Server Error</h1>
        <p>Something went wrong on our end. We are working to fix it.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Return Home</a>
    </div>
@endsection