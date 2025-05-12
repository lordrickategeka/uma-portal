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
                        {{-- <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}"> --}}
                    </div>
                    <div class="blog__one-single-blog-content-top">
                        <span>
                            <i class="far fa-user"></i>
                            {{-- @if ($blog->author)
                                {{ $blog->author->name }}
                            @else
                                Unknown Author
                            @endif --}}
                        </span>
                        <span>
                            <i class="far fa-calendar-alt"></i>
                            {{-- @if ($blog->created_at)
                                {{ $blog->created_at->format('d M Y') }}
                            @else
                                Date not available
                            @endif --}}
                        </span>
                    </div>
                    <div class="blog__details-content">
                        <h3>Continuing Professional Development (CPD)</h3>
                        <p> B a c k g r o u n d :
                            Continuing Professional Development (CPD) in healthcare has traditionally been regarded as the
                            way in which healthcare workers continue to learn and develop throughout their careers so that
                            they build both their skills and knowledge up to date in order to practice safely and
                            effectively. This was not mandatory and neither was it enforced or regulated and mainly depended
                            on one’s need and interest to keep updated. As national borders continue to disappear as far as
                            health provision and education are concerned against a rapidly advancing technological and
                            digital world, changing societal needs, this lends credence to the idea that healthcare workers
                            require CPD in order to ensure the delivery of safe, quality services in a more relevant and
                            regulated fashion.
                            <br />
                            Doctors are very crucial in the healthcare system and are often taken by competing activities
                            like training other medical carders, long patient lines and chronic fatigue thus undermining the
                            effort in establishing a sustained high-quality CPD culture in resource-limited settings (RLS).
                            Nonetheless developing a culture of continuous professional education is the cornerstone of
                            revolutionalising and improving healthcare delivery, enhancing accountability and regulation
                            strengthening. This should be prioritised as a low-cost intervention model to improve health
                            service delivery. A fundamental component for CPD that needs to be in place is ensuring that a
                            doctor must poses a recognized certificate of being registered according to the law of the land.
                            <br />
                            In addition, CPD conducted by the Uganda Medical Association (U.M.A.) must ensure relevance and
                            timeliness of updated information is provided to address the current trends in health is of
                            paramount importance. U.M.A has five focus areas, which are to contribute to universal access to
                            health and health care, promote professional ethical standards among medical doctors in Uganda,
                            promote the welfare of medical doctors in Uganda, to mobilize doctors to join and encourage them
                            to actively participate in the association’s activities and to strengthen the financial base of
                            the association.
                            <br />
                            In order to work towards achieving the 5 focus areas, there is a need for doctors to routinely
                            meet and be involved in CPD activities as a platform for enhancing change. We will therefore
                            define CPD beyond educational activities to enhance medical competence in medical knowledge and
                            skills, but also in management, team building, professionalism, interpersonal communication,
                            technology, teaching, and accountability. In Uganda doctor are mandated to get
                            registered/licensed annually according to the Uganda Medical and Dental Practitioners Council
                            (UMDPC) Act 1998. As of 2019 531 practitioners were registered at the Council from July 2018 to
                            June 2019. While those who are updated members in February 2020 under U.M.A. are 55 out of
                            previously registered 1812 yet it is estimated that there are nearly 7000 doctors working in
                            Uganda. Do the maths!!!!
                        </p>


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
                        {{-- <p>{{ $blog->additional_content }}</p> --}}

                        <!-- Display tags -->

                    </div>

                </div>
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
