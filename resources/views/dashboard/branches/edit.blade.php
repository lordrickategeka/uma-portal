@extends('layouts.dash')
@section('content')
<div class="container">
    <h2>Edit Branch</h2>
    <form action="{{ route('branches.update', $branch) }}" method="POST">
        @csrf @method('PUT')
        @include('dashboard.branches.partials.form', ['button' => 'Update'])
    </form>
</div>
@endsection