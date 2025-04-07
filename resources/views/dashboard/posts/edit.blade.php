@extends('layouts.dash')

@section('content')
    <div class="container">
        <h1>Edit News</h1>
        @include('dashboard.posts.partials.form', ['categories' => $categories, 'blog' => $blog])  
    </div>
@endsection
