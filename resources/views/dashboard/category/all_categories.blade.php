@extends('layouts.dash')
@section('content')
    <div class="container">
        <h1>All Post Categories</h1>

        <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Create Category</a>
        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            @if($categories->count() > 0)
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->description }}</td>
                    <td>
                        <span class="badge bg-{{ $category->status == 'active' ? 'success' : 'danger' }}">
                        {{ ucfirst($category->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('categories.show', $category->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            @else
            No Categories Yet! create your first post category <a href="{{route('categories.create')}}">
                Create Post Category</a>
            @endif
        </table>
    </div>
@endsection