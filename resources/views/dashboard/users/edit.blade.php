@extends('layouts.dash')

@section('content')
    <div class="p-6">
        <h2 class="text-xl font-bold mb-4">Assign Roles to {{ $user->name ?? $user->email }}</h2>

        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-4">
                @foreach ($roles as $role)
                    <label class="flex items-center space-x-2 mb-2">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                               {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                        <span>{{ $role->name }}</span>
                    </label>
                @endforeach
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Update Roles
            </button>
        </form>
    </div>
    @endsection
