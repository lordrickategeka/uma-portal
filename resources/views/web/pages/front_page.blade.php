@extends('layouts.web-pages')
@section('content')
    <!-- Banner Three Area Start -->
    <div class="banner__three">
        <div class="swiper banner__three-slider">
            <div class="swiper-wrapper">
                <div class="slider-arrow"> <i class="swiper-button-prev fal fa-long-arrow-left"></i> <i
                        class="swiper-button-next fal fa-long-arrow-right"></i> </div>
                <div class="banner__three-single-slide swiper-slide"
                    style="background-image: url('{{ asset('web-pages/assets/images/jash-8th.jpg') }}');">
                    <div class="banner__three-shape"> <img class="shape-1" data-animation="fadeInUp" data-delay="1.7s"
                            src="assets/img/shapes/banner-shape-1.png" alt=""> <img class="shape-2"
                            data-animation="fadeInRightBig" data-delay="1.7s" src="assets/img/shapes/banner-shape-2.png"
                            alt=""> </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-7 col-lg-8 col-md-10 col-sm-11">
                                <div class="banner__three-content"> <span class="subtitle-three" data-animation="fadeInUp"
                                        data-delay=".3s">Welcome to Uganda Medical
                                        Association</span>
                                    <h2 data-animation="fadeInUp" data-delay=".6s">Advocating for Doctors,
                                        Transforming Healthcare</h2>
                                    <p data-animation="fadeInUp" data-delay=".8s">Championing the rights of medical
                                        professionals while driving quality healthcare for all Ugandans.</p>
                                    <a href="#" class="btn-two" data-animation="fadeInUp" data-delay="1s">Partner with
                                        Us Today
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="banner__three-single-slide swiper-slide"
                    style="background-image: url({{ asset('web-pages/assets/images/launch.jpg') }});">
                    <div class="banner__three-shape"> <img class="shape-1" data-animation="fadeInUp" data-delay="1.7s"
                            src="assets/img/shapes/banner-shape-1.png" alt=""> <img class="shape-2"
                            data-animation="fadeInRightBig" data-delay="1.7s" src="assets/img/shapes/banner-shape-2.png"
                            alt=""> </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-7 col-lg-8 col-md-10 col-sm-11">
                                <div class="banner__three-content"> <span class="subtitle-three" data-animation="fadeInUp"
                                        data-delay=".3s">Welcome to Uganda Medical
                                        Association</span>
                                    <h2 data-animation="fadeInUp" data-delay=".5s">Continuous Medical Education &
                                        Professional Growth</h2>
                                    <p data-animation="fadeInUp" data-delay=".8s">Empowering doctors with the latest
                                        medical knowledge through workshops, research, and mentorship programs.</p>
                                    <a href="#" class="btn-two" data-animation="fadeInUp" data-delay="1s">Start Your
                                        Journey Today
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="banner__three-single-slide swiper-slide"
                    style="background-image: url({{ asset('web-pages/assets/images/launch.jpg') }});">
                    <div class="banner__three-shape"> <img class="shape-1" data-animation="fadeInUp" data-delay="1.7s"
                            src="assets/img/shapes/banner-shape-1.png" alt=""> <img class="shape-2"
                            data-animation="fadeInRightBig" data-delay="1.7s" src="assets/img/shapes/banner-shape-2.png"
                            alt=""> </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-7 col-lg-8 col-md-10 col-sm-11">
                                <div class="banner__three-content"> <span class="subtitle-three" data-animation="fadeInUp"
                                        data-delay=".3s">Welcome to Uganda Medical
                                        Association</span>
                                    <h2 data-animation="fadeInUp" data-delay=".5s">Strengthening Public Health &
                                        Medical Ethics</h2>
                                    <p data-animation="fadeInUp" data-delay=".8s">Promoting ethical medical practice
                                        and ensuring quality healthcare access for every Ugandan.</p>
                                    <a href="#" class="btn-two" data-animation="fadeInUp" data-delay="1s">Start Your
                                        Journey Today
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner Three Area End -->
    <!-- Services Five Start -->
    <div class="service__five">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <div class="col-xl-4 col-lg-6">
                    <div class="service__five-card">
                        <div class="icon"> <i class="flaticon-imac-computer"></i> </div>
                        <div class="content">
                            <h5>Championing Doctors' Rights.</h5>
                            <p>Advocating for improved salaries, workplace conditions, and legal protections.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="service__five-card">
                        <div class="icon"> <i class="flaticon-imac-computer"></i> </div>
                        <div class="content">
                            <h5> Promoting Medical Excellence.</h5>
                            <p>Offering Continuous Professional Development (CPD) programs and research opportunities.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="service__five-card">
                        <div class="icon"> <i class="flaticon-imac-computer"></i> </div>
                        <div class="content">
                            <h5>Supporting Doctors’ Welfare.</h5>
                            <p>Providing financial aid, legal support, and mental health resources.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Services Five End -->
    <!-- About Three Area Start -->
    <div class="about__three section-padding">
        <span class="row" id="about-us"></span>
        <div class="container">
            <div class="row align-items-center flex-wrap-reverse gy-4">
                <div class="col-xl-5 col-lg-7 col-md-8">
                    <div class="about__two-image"> <img src="{{ asset('web-pages/assets/images/uma-HQ.jpg') }}"
                            alt="">
                        <div class="about__two-image-shape shape-1 animate-zoom-in-out">

                        </div>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-12">
                    <div class="about__three-content"> <span class="subtitle-two">About Uganda Medical
                            Association.</span>
                        <h2 style="font-size: 50px">Welcome to Uganda Medical Association (UMA) TESTING.</h2>
                        <p>
                            The Uganda Medical Association (UMA) is the premier professional body representing qualified
                            and
                            registered medical practitioners in Uganda. Since 1964, UMA has been at the forefront of
                            medical advocacy,
                            professional development, and healthcare policy influence, ensuring better working
                            conditions for doctors
                            and improved healthcare for all Ugandans.
                        </p>

                        <div class="about__two-content-service">
                            <div class="about__two-content-service-single">
                                <div class="service-top"> <i class="flaticon-content-marketing"></i>
                                    <h6>Our Vision</h6>
                                </div>
                                <p>All people in Uganda have access to quality health and healthcare.</p>
                            </div>


                            <div class="about__two-content-service-single">
                                <div class="service-top"> <i class="flaticon-growth"></i>
                                    <h6>Core Values</h6>
                                </div>
                                <p>
                                    🔹 Integrity | 🔹 Teamwork | 🔹 Transparency
                                    🔹 Gender Sensitivity | 🔹 Democracy | 🔹 Accountability

                                </p>
                            </div>

                        </div>

                        <div style="display: grid" class="vision-box col-md-12">

                            <div class="about__two-content-service-single">
                                <div class="service-top"> <i class="flaticon-growth"></i>
                                    <h6>Our Mission</h6>
                                </div>
                                <p>To empower medical doctors through programs that enhance their social welfare,
                                    professional growth, and advocacy capabilities, ensuring they deliver exceptional
                                    care to communities.
                                </p>
                            </div>
                        </div>

                        <a href="about.html" class="btn-two">
                            About us
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About Three Area End -->
    <!-- Services Three Area Start -->
    <div class="services__four section-padding">
        <div class="container">
            <div class="row justify-content-center text-center mb-50">
                <div class="col-xl-6 col-lg-7 col-md-9"> <span class="subtitle-four">What We Offer</span>
                    <h2>Key Objectives & Services</h2>
                </div>
            </div>
            <div class="row gy-4 justify-content-center">
                <div class="col-xl-6 col-lg-6">
                    <div class="services__four-single-service">
                        <div class="services__four-single-service-top">
                            <div class="services__four-single-service-icon"> <i class="flaticon-software-development"></i>
                            </div> <a href="service-details.html">Advocating for Doctors' Welfare</a>
                        </div>
                        <p>
                        <ul>
                            <li> Negotiating Better Salaries & Working Conditions – Representing doctors in discussions
                                with policymakers.</li>


                            <li> Legal Protection & Workplace Rights – Ensuring medical professionals receive fair
                                treatment.</li>
                        </ul>
                        </p> <span class="number">01</span>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6">
                    <div class="services__four-single-service">
                        <div class="services__four-single-service-top">
                            <div class="services__four-single-service-icon"> <i class="flaticon-software-development"></i>
                            </div> <a href="service-details.html">Strengthening Public Health Systems.</a>
                        </div>
                        <p>
                        <ul>
                            <li>Partnering with government & NGOs to improve drug regulation, HIV/AIDS management, and
                                universal healthcare.</li>


                            <li> Leading vaccination & disease prevention campaigns.</li>
                            <ul>
                                </p> <span class="number">02</span>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6">
                    <div class="services__four-single-service">
                        <div class="services__four-single-service-top">
                            <div class="services__four-single-service-icon"> <i class="flaticon-software-development"></i>
                            </div> <a href="service-details.html">Continuous Medical Education (CME) & Training.</a>
                        </div>
                        <p>
                        <ul>
                            <li>Regular CPD workshops & certifications to keep doctors updated on emerging medical
                                trends.</li>


                            <li>Specialized training in leadership, ethics, and research methods. </li>
                        </ul>
                        </p> <span class="number">03</span>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6">
                    <div class="services__four-single-service">
                        <div class="services__four-single-service-top">
                            <div class="services__four-single-service-icon"> <i class="flaticon-software-development"></i>
                            </div> <a href="service-details.html">Upholding Medical Ethics & Patient Safety.</a>
                        </div>
                        <p>
                        <ul>
                            <li>Enforcing a code of conduct for doctors.</li>


                            <li>Providing training on medical ethics and professionalism.</li>
                        </ul>

                        </p> <span class="number">04</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="testimoinal-brand-wrapper section-padding pb-50"
            style="background-image: url(assets/img/testimonial/testimoinal-four-bg.png);">

            <div class="brand-area">
                <div class="container">
                    <div class="swiper brand-slider-active" style="transition-duration: 1500ms">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide brand-single-slider"> <img
                                    src="{{ asset('web-pages/assets/images/achest.jpg') }}" alt=""> </div>
                            <div class="swiper-slide brand-single-slider"> <img
                                    src="{{ asset('web-pages/assets/images/amref1.jpg') }}" alt=""> </div>
                            <div class="swiper-slide brand-single-slider"> <img
                                    src="{{ asset('web-pages/assets/images/cardno2.jpg') }}" alt=""> </div>
                            <div class="swiper-slide brand-single-slider"> <img
                                    src="{{ asset('web-pages/assets/images/path-2.jpg') }}" alt=""> </div>
                            <div class="swiper-slide brand-single-slider"> <img
                                    src="{{ asset('web-pages/assets/images/seed.jpg') }}" alt=""> </div>
                            <div class="swiper-slide brand-single-slider"> <img
                                    src="{{ asset('web-pages/assets/images/uhf.jpg') }}" alt=""> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Three Area End -->
    <!-- Contact One Area End -->
    <div class="contact__one">
        <div class="contact__one-wrapper">
            <div class="contact__one-image"> 
                <img src="{{ asset('web-pages/assets/images/call-us.jpg') }}"
                    alt=""> 
                </div>
            <div class="contact__one-content"> <span class="subtitle-three">Lets' Talk!</span>
                <h2>Contact Us</h2>
                <form action="#" class="contact__one-form">
                    <input type="text" placeholder="Name">
                    <input type="text" placeholder="Email">
                    <input type="text" placeholder="Phone">
                    <input type="text" placeholder="Subject">
                    <textarea placeholder="Message"></textarea>
                    <button class="btn-two" type="submit">Submit Now</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Contact One Area End -->
    <!-- Testimonial Two Area Start -->
    {{-- <div class="testimonial__three section-padding">
        <div class="container">
            <div class="row align-items-center mb-60 gy-5">
                <div class="col-xl-6 col-lg-6 col-md-8">
                    <div class="testimonial__three-title">
                        <span class="">Events You Missed over</span>
                        <h2>Events</h2>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-4">
                    <div class="slider-arrow justify-content-md-end"> <i
                            class="swiper-button-prev fas fa-chevron-left"></i> <i
                            class="swiper-button-next fas fa-chevron-right"></i> </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="swiper testimonial__three-slider-active">
                        <div class="swiper-wrapper">
                            <div class="testimonial__three-slider swiper-wrapper">
                                @if($blogs->count() > 0)
                                @foreach($blogs as $event)
                                    <div class="testimonial__three-single-slider swiper-slide">
                                        <div class="testimonial__three-single-slider-user">
                                            <div class="testimonial__three-single-slider-user-image">
                                                <img src="{{ $event->author->profile_photo_url ?? asset('assets/img/testimonial/avatar-1.jpg') }}" alt="{{ $event->author->name ?? 'User' }}">
                                            </div>
                                            <div class="testimonial__three-single-slider-user-name">
                                                <h4>{{ $event->author->name ?? 'Unknown Author' }}</h4>
                                                <span>{{ $event->categories->first()->name ?? 'Event Category' }}</span>
                                            </div>
                                        </div>
                                        <p>{{ Str::limit(strip_tags($event->excerpt ?? $event->content), 150) }}</p>
                                        <div class="testimonial__three-single-slider-user-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= 4 ? '' : 'not-rated' }}"></i>
                                            @endfor
                                        </div>
                                        <div class="slider-shape"> <i class="fas fa-quote-right"></i> </div>
                                    </div>
                                @endforeach
                                @else
                                No Events
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Project Three Area End -->
    <!-- Why Choose Us Three Area Start -->
    <div class="why-choose-us__three section-padding">
        <div class="container">
            <div class="row align-items-center flex-wrap-reverse gy-4">
                <div class="col-xl-6 col-lg-6 col-md-10">
                    <div class="why-choose-us__three-content"> <span class="subtitle-two">Let's Connect</span>
                        <h2>Keep in informed about our activity.</h2>
                        <p>Explore, Follow, Like and keep update about our activities on all our social media handles.
                        </p>

                        <div class="header__area-menubar-right-sidebar-popup-social">
                            <ul>
                                <li><a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                </li>
                                <li><a href="https://x.com" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                                </li>
                                <li><a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                </li>
                            </ul>
                        </div>
                        {{-- <div class="why-choose-us__three-content-service">
							<div class="why-choose-us__three-content-service-single">
								<div class="service-top"> <i class="flaticon-content-marketing"></i>
									<h6>Facebook</h6> </div>
							</div>
							<div class="why-choose-us__three-content-service-single">
								<div class="service-top"> <i class="flaticon-growth"></i>
									<h6>Twiter</h6> </div>
							</div>
							<div class="why-choose-us__three-content-service-single">
								<div class="service-top"> <i class="flaticon-growth"></i>
									<h6>Youtube</h6> </div>
							</div>
						</div>  --}}
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="why-choose-us__three-image"> <img
                            src="{{ asset('web-pages/assets/images/uma-youtube.png') }}" alt="">
                        <a href="https://youtu.be/1w3a0egOIKw" class="video-btn video-popup video-pulse">
                            <i class="fas fa-play"></i> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Why Choose Us Three Area End -->
    <!-- Blog Three Area Start -->
    <div class="blog__two section-padding pt-lg-5">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-xl-6 col-lg-6">
                    <div class="blog__two-title"> <span class="subtitle-two m-auto">Latest Blog</span>
                        <h2>Strategy and Insights</h2>
                    </div>
                </div>
            </div>
            <div class="row gy-4 justify-content-center">
                @foreach ($blogs as $article)
                    <div class="col-xl-4 col-lg-6">
                        <div class="blog__two-single-blog">
                            <div class="blog__two-single-blog-image">
                                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
                            </div>
                            <div class="blog__two-single-blog-date">
                                <span>{{ \Carbon\Carbon::parse($article->published_at)->format('d M') }}</span>
                            </div>
                            <div class="blog__two-single-blog-content">
                                <div class="blog__two-single-blog-content-top">
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
                                    <a href="{{ route('blog.show', ['id' => $article->id, 'slug' => $article->slug]) }}" class="blog-heading">
                                        {{ \Illuminate\Support\Str::words($article->title, 4) }}
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

                                <a href="{{ route('blog.show', ['slug' => $article->slug, 'id' => $article->id]) }}"
                                    class="blog-btn">
                                    Read More <i class="fas fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Blog Three Area End -->
    <!-- Footer Three Area Start -->
@endsection
