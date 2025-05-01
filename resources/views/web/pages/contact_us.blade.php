@extends('layouts.web-pages')

@section('content')
<div class="breadcrumb__area" style="background-image: url(assets/img/banner/breadcrumb.jpg);">
    <div class="container">
        <div class="breadcrumb__area-content">
            <h2>Contact Us</h2>
            <span>
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i>
                    Home
                </a>
                <i class="fas fa-chevron-right"></i>
                Contact Us
            </span>
        </div>
    </div>
</div>

<div class="contact__two section-padding">
    <div class="container">

        @if(session('success'))
        <div class="alert alert-primary">
            {{ session('success') }}
        </div>
    @endif

        <div class="row gy-4 align-items-center">
            <div class="col-xl-7">
                <div class="contact__two-content">
                    <div class="contact__two-title"> <span class="subtitle-two">Start a Conversation</span>
                        <h2>Get in Touch Now</h2>
                        <p>Need personalized advice? Our dedicated team is here to assist you. Reach out today for expert support and tailored solutions to meet your needs </p>
                    </div>
                    <div class="contact__two-form">
                        <form action="{{ route('contact.submit') }}" method="POST">
                            @csrf
                            <div class="row gy-4 mb-4">
                                <div class="col-xl-6">
                                    <input type="text" name="name" placeholder="Your Name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-xl-6">
                                    <input type="email" name="email" placeholder="Your E-mail" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-xl-6">
                                    <input type="tel" name="phone" placeholder="Phone Number" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-xl-6">
                                    <input type="text" name="subject" placeholder="Subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <textarea name="message" placeholder="Your Message" required>{{ old('message') }}</textarea>
                            @error('message')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <button type="submit" class="btn-two w-100 justify-content-center"> Submit Now <i class="fas fa-chevron-right"></i> </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="contact__two-contact-info">
                    <div class="contact__two-single-info">
                        <div class="contact__two-single-info-icon"> <i class="flaticon-email"></i> </div>
                        <div class="contact__two-single-info-content">
                            <h4>Email Address</h4> <span><a href="mailto:uma1960@gmail.com">uma1960@gmail.com</a></span></div>
                    </div>
                    <div class="contact__two-single-info">
                        <div class="contact__two-single-info-icon"> <i class="flaticon-phone-call"></i> </div>
                        <div class="contact__two-single-info-content">
                            <h4>Phone Number</h4> <span><a href="tel:+656(354)981516">+256 (0) 706 744 927</a></span></div>
                    </div>
                    <div class="contact__two-single-info">
                        <div class="contact__two-single-info-icon"> <i class="fal fa-map-marker-alt"></i> </div>
                        <div class="contact__two-single-info-content">
                            <h4>Office Location</h4> <span><a href="https://maps.google.com">Chrisams Designs Building,
                                <br/> Kafeeero Road, Old Mulago. Kampala, Uganda.</a></span> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection