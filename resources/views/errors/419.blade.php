@extends('layouts.web-pages')

@section('content')
    <div class="container text-center">
        <h1 class="text-warning">419 - Page Expired</h1>
        <p>Your session has expired. Please refresh the page and try again.</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary">Go Back</a>
    </div>
@endsection