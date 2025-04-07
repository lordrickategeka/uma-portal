@extends('layouts.web-pages')

@section('content')
<div class="container text-center">
    <h1 class="text-danger">404 - Page Not Found</h1>
    <p>Oops! The page you’re looking for doesn’t exist.</p>

    @if(Auth::check()) <!-- Check if the user is authenticated -->
        <a href="{{ route('home') }}" class="btn btn-primary">Go Back Home</a>
    @else
        <a href="{{ route('login') }}" class="btn btn-primary">Go to Login</a>
    @endif
</div>

@endsection