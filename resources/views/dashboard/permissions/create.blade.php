@extends('layouts.dash')

@section('content')
    <div class="max-w-7xl mx-auto p-6 bg-white shadow rounded-lg">
        <h1 class="text-xl font-bold mb-4">Create Permission</h1>

        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Permission Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Create Permission</button>
        </form>
    </div>
@endsection
