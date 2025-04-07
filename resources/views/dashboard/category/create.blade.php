@extends('layouts.dash')
@section('content')
<div class="container">
    <h2>Create Category</h2>
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        @include('dashboard.category.partials.form', ['button' => 'Create'])
    </form>
</div>
@endsection

