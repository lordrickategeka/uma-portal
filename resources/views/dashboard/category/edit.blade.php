@extends('layouts.dash')
@section('content')
<div class="container">
    <h2>Edit Category</h2>
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf @method('PUT')
        @include('dashboard.category.partials.form', ['button' => 'Update'])
    </form>
</div>
@endsection