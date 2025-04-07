@extends('layouts.dash')
@section('content')
    <div class="container">
        <h1>News Articles</h1>

        <a href="{{ route('post.create') }}" class="btn btn-primary mb-3">Create News</a>
        <table class="table table-hover">
            <tr>
                <th><input type="checkbox" id="select-all"> No.</th>
                <th>Title</th>
                <th>Category / Tags</th>
                <th>Branch</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            @foreach ($blogs as $index => $article)
                <tr>
                    <td><input type="checkbox" class="order-select" data-id="{{ $article->id }}"> {{ $index + 1 }}</td>
                    <td>{{ $article->title }}
                        <p><strong>Type:</strong> {{ ucfirst($article->post_type) }}</p>
                    </td>
                    <td>
                        @if ($article->categories && count($article->categories))
                        <small>Categories:</small>
                            @foreach ($article->categories as $category)
                                <span class="badge bg-secondary">{{ $category->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">No Categories</span>
                        @endif
                        <hr/>
                        @if ($article->tags && $article->tags->count())
                        <small>Tags:</small>
                        @foreach ($article->tags as $tag)
                            <span class="badge bg-info text-dark">{{ $tag->name }}</span>
                        @endforeach
                    @else
                        <span class="text-muted">No Tags</span>
                    @endif
                    </td>
                   
                    
                    <td>{{ $article->branch->name ?? 'Not assigned' }}</td>
                    <td>{{ ucfirst($article->status) }}</td>
                    <td>
                        <a href="{{ route('post.show', $article->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('post.edit', $article->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('post.destroy', $article->id) }}" method="POST" style="display:inline;"
                            onsubmit="return confirmDelete(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        {{-- @php use Illuminate\Pagination\Paginator; @endphp
        {{ $blogs->links('') }} --}}
    </div>
@endsection
