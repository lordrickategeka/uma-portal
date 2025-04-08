@extends('layouts.dash')

@section('content')
    <div class="max-w-7xl mx-auto p-6 bg-white shadow rounded-lg">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">Permissions</h1>
            <a href="{{ route('permissions.create') }}" class="btn btn-primary px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Create New Permission</a>
        </div>

        @if (session('success'))
            <div class="mb-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-left">Permission Name</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $permission->name }}</td>
                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('permissions.edit', $permission) }}" class="px-2 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Edit</a>
                            <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            <a href="{{ route('permissions.assign.form', $permission) }}" class="px-2 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600">Assign Roles</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
