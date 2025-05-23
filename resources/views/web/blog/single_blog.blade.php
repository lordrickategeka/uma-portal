@extends('layouts.web-pages')

@section('content')
    <div class="breadcrumb__area" style="background-image: url(assets/img/banner/breadcrumb.jpg);">
        <div class="container">
            <div class="breadcrumb__area-content">
                <h2>{{ $blog->title }}</h2>
                <span>
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                    <i class="fas fa-chevron-right"></i>
                    {{ $blog->title }}
                </span>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <div class="blog__details section-padding">
        <div class="container">
            <div class="row gy-4 flex-wrap-reverse">
                <div class="col-xl-8">
                    <div class="blog__details-thumb">
                        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}">
                    </div>
                    <div class="blog__one-single-blog-content-top">
                        <span>
                            <i class="far fa-user"></i>
                            @if ($blog->author)
                                {{ $blog->author->name }}
                            @else
                                Unknown Author
                            @endif
                        </span>
                        <span>
                            <i class="far fa-calendar-alt"></i>
                            @if ($blog->created_at)
                                {{ $blog->created_at->format('d M Y') }}
                            @else
                                Date not available
                            @endif
                        </span>
                    </div>
                    <div class="blog__details-content">
                        <h3>{{ $blog->title }}</h3>
                        <p>{!! $blog->content !!}</p>
                        @if ($blog->quote)
                            <div class="blog__details-quote">
                                <p>{{ $blog->quote }}</p>
                                <h5>- {{ $blog->quote_author }}</h5>
                                <span>{{ $blog->quote_position }}</span>
                            </div>
                        @endif
        
                        <!-- Display associated images -->
                        <div class="blog__details-image">
                            @if ($blog->images && $blog->images->count() > 0)
                                @foreach ($blog->images as $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="">
                                @endforeach
                            @else
                                <p>No images available</p>
                            @endif
                        </div>
                        
                        <!-- Display additional content -->
                        <p>{{ $blog->additional_content }}</p>
        
                        <!-- Display tags -->
                        <div class="sidebar-item-single sidebar-blog-tags">
                            <div class="sidebar-item-single-title">
                                <h5>Tags</h5>
                            </div>
                            <div class="tags">
                                @foreach ($blog->tags as $tag)
                                    {{-- <a href="{{ route('blog.tag', $tag->id) }}">{{ $tag->name }}</a> --}}
                                    {{ $tag->name }},
                                @endforeach
                            </div>
                        </div>
                    </div>
        
        
                    {{-- <div class="blog__details-comment-form">
                        <h4>Leave A Comment</h4>
                        <span>Fields (*) Mark are Required</span>
                        <form action="#" method="POST">
                            @csrf
                            <input type="text" name="name" placeholder="Full Name*" required>
                            <input type="email" name="email" placeholder="Email Address*" required>
                            <input type="url" name="website" placeholder="https://">
                            <textarea name="comment" placeholder="Your Comment" required></textarea>
                            <button type="submit" class="btn-one">Submit <i class="fas fa-chevron-right"></i></button>
                        </form>
                    </div> --}}
                </div>
                @include('inc.single_page_sidebar')
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
                    <form action="#" class="subscribe__one-form">
                        <input type="email" placeholder="Enter Your Email">
                        <button class="btn-one" type="submit">Subscribe now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
