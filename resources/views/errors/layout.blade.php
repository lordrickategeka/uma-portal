@extends('layouts.app')

@section('content')
    <div class="container text-center">
        <h1 class="text-danger">{{ $exception->getStatusCode() }} - Oops!</h1>
        <p>Something went wrong.</p>
        
        <a href="{{ route('home') }}" class="btn btn-primary">Go Home</a>
    </div>
@endsection
