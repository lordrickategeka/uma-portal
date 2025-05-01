@extends('layouts.dash')

@section('content')
    <div class=" bg-white shadow rounded-lg">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">Permissions</h1>
            <a href="{{ route('permissions.create') }}"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Create New Permission</a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 text-green-600">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <table class="table table-hover">
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
                            <a href="{{ route('permissions.edit', $permission) }}"
                                class="px-2 py-1 btn btn-primary text-white rounded-lg hover:bg-yellow-600">Edit</a>
                        </td>
                        <td>
                            <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 btn btn-success text-white rounded-lg hover:bg-red-600"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    <td>
                            <a href="{{ route('permissions.assign.form', $permission) }}"
                                class="px-2 py-1 btn btn-info text-white rounded-lg hover:bg-green-600">Assign Roles</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $permissions->links('vendor.pagination.simple-tailwind') }}
    </div>
@endsection
