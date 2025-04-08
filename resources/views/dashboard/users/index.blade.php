@extends('layouts.dash')

@section('content')
    <div class="p-6">
        <h2 class="text-xl font-bold mb-4">User Role Management</h2>
        <table class="w-full table-auto border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">User</th>
                    <th class="px-4 py-2">Roles</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $user->name ?? $user->email }}</td>
                        <td class="px-4 py-2">
                            {{ $user->roles->pluck('name')->implode(', ') ?: 'None' }}
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ route('users.edit', $user) }}"
                               class="text-blue-600">Edit Roles</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endsection
