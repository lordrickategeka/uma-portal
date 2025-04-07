@extends('layouts.dash')
@section('content')
<div class="container">
    <h2>Create Branch</h2>
    <form action="{{ route('branches.store') }}" method="POST">
        @csrf
        @include('dashboard.branches.partials.form', ['button' => 'Create'])
    </form>
</div>
@endsection