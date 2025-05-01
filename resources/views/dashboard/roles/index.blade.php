@extends('layouts.dash')

@section('content')
   
        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-bold">Roles</h2>
            <a href="{{ route('roles.create') }}" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded">Create Role</a>
        </div>

        @foreach ($roles as $role)
            <div class="p-4 mb-2 bg-white shadow rounded">
                <strong>{{ $role->name }}</strong>
                <p class="text-sm text-gray-600">
                    Permissions: {{ $role->permissions->pluck('name')->implode(', ') }}
                </p>
                <div class="mt-2">
                    <a href="{{ route('roles.edit', $role) }}" class="text-blue-500">Edit</a> |
                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline-block">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
@endsection
