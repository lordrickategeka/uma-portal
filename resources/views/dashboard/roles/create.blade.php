@extends('layouts.dash')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Create New Role</h2>
    @include('dashboard.roles.form')
</div>
@endsection