@extends('layouts.dash')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
@section('content')
    <div class="container">
        <h1>Create News</h1>
        @include('dashboard.posts.partials.form', ['categories' => $categories])
    </div>
@endsection
