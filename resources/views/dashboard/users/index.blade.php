@extends('layouts.dash')

@section('content')
    
        <h2 class="text-xl font-bold mb-4">User Role Management</h2>
        <div class="card">
            <table class="table table-hover">
            <thead class="bg-gray-100">
                <tr>
                    <th><input type="checkbox" id="select-all"> No.</th>
                    <th class="px-4 py-2">User</th>
                    <th class="px-4 py-2">Roles</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                    <tr class="border-t">
                        <td><input type="checkbox" class="order-select" data-id="{{ $user->id }}"> {{ $index + 1 }}</td>
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
