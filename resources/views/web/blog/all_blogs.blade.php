@extends('layouts.web-pages')

@section('content')
<div class="breadcrumb__area" style="background-image: url(assets/img/banner/breadcrumb.jpg);">
    <div class="container">
        <div class="breadcrumb__area-content">
            <h2>All Articles</h2>
            <span>
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i>
                    Home
                </a>
                <i class="fas fa-chevron-right"></i>
                Articles
            </span>
        </div>
    </div>
</div>

<div class="blog__one section-padding">
    <div class="container">
        <div class="row gy-4">
            @foreach ($blogs as $article)
            <div class="col-xl-4 col-lg-6">
                <div class="blog__one-single-blog">
                    <div class="blog__one-single-blog-image">
                        <img src="{{$article->image_url}}" alt="{{ $article->title }}">
                    </div>
                    <div class="blog__one-single-blog-date">
                        <span>{{ \Carbon\Carbon::parse($article->published_at)->format('d M') }}</span>
                    </div>
                    <div class="blog__one-single-blog-content">
                        <div class="blog__one-single-blog-content-top">
                            <span>
                                <i class="far fa-user"></i>
                                {{ $article->author->name ?? 'Admin' }}
                            </span>
                            <span>
                                <i class="far fa-comment-dots"></i>
                                Comments ({{ $article->comments_count ?? 0 }})
                            </span>
                        </div>
                        <h5>
                            <a href="{{ route('blog.show', ['id' => $article->id, 'slug' => Str::slug($article->title)]) }}">
                                {{ \Illuminate\Support\Str::words($article->title, 5) }}
                            </a>
                        </h5>
                        @if ($article->tags->count())
                        <small>Category:</small>
                        @foreach ($article->categories as $category)
                        <span class="badge bg-primary">
                            {{ $category->name ?? 'Uncategorized' }}
                        </span>
                        @endforeach
                        @endif

                        @if ($article->tags->count())
                        <div class="mt-2">
                            <small>Tags:</small>
                            @foreach ($article->tags as $tag)
                                <span class="badge bg-secondary">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        @endif

                        <a href="{{ route('blog.show', ['slug' => $article->slug, 'id' => $article->id]) }}" class="blog-btn">
                            Read More <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $blogs->links() }}
        </div>
    </div>
</div>

<div class="subscribe__one" style="background-image: url('assets/img/subscribe/subscribe-bg.png');">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8">
                <div class="subscribe__one-title text-center">
                    <h2>Stay Connected! Subscribe For <span>The Latest Updates</span></h2>
                </div>
                @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    
                    <form action="{{ route('subscription.submit') }}" class="subscribe__one-form" method="POST">
                        @csrf
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Your Email">
                    
                    <button class="btn-one" type="submit">Subscribe now</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
