@extends('layouts.dash')

@section('content')
    <div class="container">
        <h1>Create News</h1>
        @include('dashboard.posts.partials.form', ['categories' => $categories])
    </div>
@endsection
