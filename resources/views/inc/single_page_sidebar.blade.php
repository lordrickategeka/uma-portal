<div class="col-xl-4">
    <div class="blog__standard-sidebar page-sidebar">
        <div class="sidebar-item-single sidebar-search">
            <form action="#" method="GET">
                <input type="text" name="query" placeholder="Search here...">
                <button style="background: #2A3892" type="submit">Search</button>
            </form>
        </div>
        <div class="sidebar-item-single sidebar-recent-blog">
            <div class="sidebar-item-single-title">
                <h5>Latest Post</h5>
            </div>
            @foreach ($latestBlogs as $latestBlog)
                <div class="recent-blog-single">
                    <div class="blog__one-single-blog-content-top">
                        <span>
                            <i class="far fa-user"></i>
                            {{ $latestBlog->author->name }}
                        </span>
                        <span>
                            <i class="far fa-calendar-alt"></i>
                            {{ $latestBlog->created_at->format('d M Y') }}
                        </span>
                    </div>
                    <a
                        href="{{ route('blog.show', ['slug' => $latestBlog->slug, 'id' => $latestBlog->id]) }}">{{ $latestBlog->title }}</a>
                </div>
            @endforeach
        </div>
        <div class="sidebar-item-single sidebar-contact">
            <h3>Call Us Today!</h3>
            <p>Call us today to discuss how we can drive your success forward</p>
            <i class="call-icon fas fa-phone-alt"></i>
            <a href="tel:+656354981516">+256 (0) 706 744 927</a>
        </div>
    </div>
</div>