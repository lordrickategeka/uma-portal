@extends('layouts.dash')

@section('content')
    <div class="max-w-7xl mx-auto p-6 bg-white shadow rounded-lg">
        <h1 class="text-xl font-bold mb-4">Assign Roles to Permission: {{ $permission->name }}</h1>

        <form action="{{ route('permissions.assign', $permission) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="roles" class="block text-gray-700">Assign Roles</label>
                <select name="roles[]" id="roles" multiple class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $permission->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('roles')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Assign Roles</button>
        </form>
    </div>
@endsection
