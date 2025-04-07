<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Start Meta -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Business Consulting HTML5 Template">
    <meta name="keywords" content="Creative, Digital, multipage, landing, freelancer template">
    <meta name="author" content="ThemeOri">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title of Site -->
    <title>Welcome to Uganda Medical Association - UMA</title>
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="assets/img/favicon-1.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('web-pages/assets/css/bootstrap.min.css') }}">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="{{ asset('web-pages/assets/css/fontawesome.css') }}">
    <!-- Flat Icon CSS -->
    <link rel="stylesheet" href="{{ asset('web-pages/assets/webfonts/flat-icon/flaticon_bantec.css') }}">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{ asset('web-pages/assets/css/animate.css') }}">
    <!-- Swiper Bundle CSS -->
    <link rel="stylesheet" href="{{ asset('web-pages/assets/css/swiper-bundle.min.css') }}">
    <!-- Slick CSS -->
    <link rel="stylesheet" href="{{ asset('web-pages/assets/css/slick.css') }}">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="{{ asset('web-pages/assets/css/magnific-popup.css') }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('web-pages/assets/sass/style.css') }}">
</head>

<body>
    <!-- Preloader Start -->
    <div class="loader"> <span class="loader-container"></span> </div>
    <!-- Preloader End -->
    <!-- Top Bar Start -->
    <div class="top__bar two">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-md-8">
                    <div class="top__bar-left">
                        <a href="https://www.google.com/maps"> <i class="fas fa-map-marker-alt"></i> Chrisams Designs
                            Building, Kafeeero Road, Old Mulago. Kampala </a>
                        <a href="mailto:info@example.com"> <i class="fas fa-envelope"></i>uma1960@gmail.com</a>
                        <a href="mailto:info@example.com"> <i class="fas fa-envelope"></i>+256 (0) 706 744 927</a>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4">
                    <div class="top__bar-right"> <a href="https://facebook.com" target="_blank"><i
                                class="fab fa-facebook-f"></i></a> <a href="https://x.com" target="_blank"><i
                                class="fa-brands fa-x-twitter"></i></a> <a href="https://linkedin.com"
                            target="_blank"><i class="fab fa-linkedin-in"></i></a> </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Top Bar End -->
    <!-- Header Area Start -->
    <div class="header__area three">
        <div class="header__sticky">
            <div class="container">
                <div class="header__area-menubar">
                    <div class="header__area-menubar-left">
                        <div class="header__area-menubar-left-logo">
                            <a href="index.html"><img class="dark-n" src="assets/img/logo-4.png" alt=""></a>
                        </div>
                    </div>
                    <div class="header__area-menubar-center">
                        <div class="header__area-menubar-center-menu menu-responsive menu-responsive-three">
                            <ul id="mobilemenu">
                                <li><a href="/">Home</a></li>
                                <li class="menu-item-has-children">
                                    <a href="#about-us">About us</a>
                                    <ul class="sub-menu">
                                        <li><a href="{{ url('#') }}">Our Partners</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ url('https://umasacco.com/') }}" target="_blank">UMA SACCO</a></li>

                                <li class="menu-item-has-children">
                                    <a href="#">UMA Membership<i class="fas fa-angle-right"></i></a>
                                    <ul class="sub-menu">
                                        <li> <a href="£">More Details</a> </li>
                                        <li> <a href="{{ url('portal') }}" target="_blank">UMA Portal</a> </li>
                                    </ul>
                                </li>

                                <li><a href="{{ url('#') }}">CPD</a></li>
                                <li><a href="{{ url('#') }}">Events</a></li>

                                <li><a href="{{ route('blogs.index') }}">News</a></li>
                                <li><a href="contact.html">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="header__area-menubar-right">
                        <div class="header__area-menubar-right-box">
                            <div class="header__area-menubar-right-box-search">
                                <div class="search"> <span class="header__area-menubar-right-box-search-icon open"><i
                                            class="flaticon-loupe"></i></span> </div>
                                <div class="header__area-menubar-right-box-search-box">
                                    <form>
                                        <input type="search" placeholder="Search Here.....">
                                        <button type="submit"> <i class="flaticon-loupe"></i> </button>
                                    </form> <span class="header__area-menubar-right-box-search-box-icon"><i
                                            class="fal fa-times"></i></span>
                                </div>
                            </div>
                            <div class="responsive-menu_popup-icon  sidebar-menu-show-hide"> <i
                                    class="fas fa-bars"></i> </div>
                            <div class="header__area-menubar-right-box-sidebar">
                                <div class="header__area-menubar-right-box-sidebar-popup-icon"> <i
                                        class="flaticon-menu"></i> </div>
                            </div>
                            <div class="need-help">
                                @auth
                                <div class="need-help-icon"><i class="flaticon-user-account"></i></div>
                                <div class="need-help-icon-txt">
                                    <a href="{{route('home')}}">Dashboard</a>
                                    {{-- <span>Call Us Anytime</span>  --}}
                                </div>
                                @else
                                <div class="need-help-icon"><i class="flaticon-user-account"></i></div>
                                <div class="need-help-icon-txt">
                                    <a href="{{route('login')}}">Account</a>
                                </div>
                                @endauth
                            </div>
                            <!-- sidebar Menu Start -->
                            <div class="header__area-menubar-right-sidebar-popup home-three">
                                <div class="sidebar-close-btn"> <i class="fal fa-times"></i> </div>
                                <div class="header__area-menubar-right-sidebar-popup-logo">
                                    <a href="index.html"> <img src="assets/img/logo-5.png" alt=""> </a>
                                </div>
                                {{-- <p> Morbi et tellus imperdiet, aliquam nulla sed, dapibus erat. Aenean dapibus sem non purus venenatis vulputate. Donec accumsan eleifend blandit. Nullam auctor ligula </p> --}}
                                <div class="header__area-menubar-right-sidebar-popup-contact">
                                    <h4 class="mb-30">Lets' Talk!</h4>
                                    <div class="header__area-menubar-right-sidebar-popup-contact-item">
                                        <div class="header__area-menubar-right-sidebar-popup-contact-item-icon"> <i
                                                class="fal fa-phone-alt icon-animation"></i> </div>
                                        <div class="header__area-menubar-right-sidebar-popup-contact-item-content">
                                            <span>Call Now</span>
                                            <h6>
                                                <a href="tel:+125(895)658568">+256 (0) 706 744 927</a>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="header__area-menubar-right-sidebar-popup-contact-item">
                                        <div class="header__area-menubar-right-sidebar-popup-contact-item-icon"> <i
                                                class="fal fa-envelope"></i> </div>
                                        <div class="header__area-menubar-right-sidebar-popup-contact-item-content">
                                            <span>Quick Email</span>
                                            <h6>
                                                <a href="mailto:uma1960@gmail.com">uma1960@gmail.com</a>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="header__area-menubar-right-sidebar-popup-contact-item">
                                        <div class="header__area-menubar-right-sidebar-popup-contact-item-icon"> <i
                                                class="fal fa-map-marker-alt"></i> </div>
                                        <div class="header__area-menubar-right-sidebar-popup-contact-item-content">
                                            <span>Office Address</span>
                                            <h6>
                                                <a href="#">
                                                    Chrisams Designs Building, Kafeeero Road, Old Mulago, <br />
                                                    P.O. Box 2243, Kampala, Uganda
                                                </a>

                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="header__area-menubar-right-sidebar-popup-social">
                                    <ul>
                                        <li><a href="https://facebook.com" target="_blank"><i
                                                    class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="https://x.com" target="_blank"><i
                                                    class="fa-brands fa-x-twitter"></i></a></li>
                                        <li><a href="https://behance.net" target="_blank"><i
                                                    class="fab fa-behance"></i></a></li>
                                        <li><a href="https://linkedin.com" target="_blank"><i
                                                    class="fab fa-linkedin-in"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="sidebar-overlay"></div>
                            <div class="menu-overlay"></div>
                            <!-- sidebar Menu End -->
                            <!-- ================== Responsive Menu ================== -->
                            <div class="responsive__menu">
                                <div class="responsive__menu-wrapper">
                                    <div class="responsive__menu_wrap text-start mb-5">
                                        <div class="logo-wrapper">
                                            <a href="index.html"> <img src="assets/img/logo-5.png"
                                                    alt="Logo"></a>
                                        </div> <i class="fas fa-times close-hide-show"></i>
                                    </div>
                                    <ul class="responsive-sidebar-menu-list">
                                        <li class="responsive-sidebar-menu-list__item"> <a href="contact.html"
                                                class="responsive-sidebar-menu-list__link">
                                                Home
                                            </a> </li>

                                        <li class="responsive-sidebar-menu-list__item has-dropdown"> <a href="#"
                                                class="responsive-sidebar-menu-list__link">
                                                About us
                                            </a>
                                            <div class="responsive-sidebar-submenu" style="display: none;">
                                                <ul class="responsive-sidebar-submenu-list">
                                                    <li class="responsive-sidebar-submenu-list__item">
                                                        <a href="#"
                                                            class="responsive-sidebar-submenu-list__link">Our Partners
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </li>

                                        <li class="responsive-sidebar-menu-list__item"> <a
                                                href="{{ url('https://umasacco.com/') }}" target="_blank"
                                                class="responsive-sidebar-menu-list__link">
                                                UMA SACCO
                                            </a> </li>

                                        <li class="responsive-sidebar-menu-list__item has-dropdown"> <a href="#"
                                                class="responsive-sidebar-menu-list__link">
                                                UMA Membership
                                            </a>
                                            <div class="responsive-sidebar-submenu" style="display: none;">
                                                <ul class="responsive-sidebar-submenu-list">
                                                    <li class="responsive-sidebar-submenu-list__item">
                                                        <a href="£"
                                                            class="responsive-sidebar-submenu-list__link">More Details
                                                        </a>
                                                    </li>
                                                    <li class="responsive-sidebar-submenu-list__item">
                                                        <a href="{{ url('portal') }}"
                                                            class="responsive-sidebar-submenu-list__link">UMA Portal
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </li>

                                        <li class="responsive-sidebar-menu-list__item"> <a href="£"
                                                class="responsive-sidebar-menu-list__link">
                                                CPD
                                            </a> </li>
                                        <li class="responsive-sidebar-menu-list__item"> <a href="£"
                                                class="responsive-sidebar-menu-list__link">
                                                Events
                                            </a> </li>
                                        <li class="responsive-sidebar-menu-list__item"> <a href="{{ route('blogs.index') }}"
                                                class="responsive-sidebar-menu-list__link">
                                                News
                                            </a> </li>
                                        <li class="responsive-sidebar-menu-list__item"> <a href="£"
                                                class="responsive-sidebar-menu-list__link">
                                                Contact Us
                                            </a> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Area End -->

    @yield('content')

    <div class="footer__three">
        <div class="container">
            <div class="footer-wrapper">
                <div class="footer__three-widget">
                    <div class="footer__three-widget-about">
                        <a href="#"><img src="assets/img/logo-5.png" alt=""></a>
                        <p>We delivering innovative solutions that drive sustainable growth and success for you</p>
                        <form action="#">
                            <input type="email" placeholder="Email Address">
                            <button type="submit"> <i class="fas fa-arrow-right"></i> </button>
                        </form>
                    </div>
                </div>
                <div class="footer__three-widget">
                    <h5>Quick Link</h5>
                    <div class="footer__three-widget-solution">
                        <ul>
                            <li><a href="about.html">About Us</a></li>
                            <li><a href="service.html">Service</a></li>
                            <li><a href="faq.html">FAQ</a></li>
                            <li><a href="blog-standard.html">Blog</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer__three-widget">
                    <h5>Services</h5>
                    <div class="footer__three-widget-solution">
                        <ul>
                            <li>
                                <a href="service-details.html"> <i class="far fa-chevron-double-right"></i> Retirement
                                    Planning </a>
                            </li>
                            <li>
                                <a href="service-details.html"> <i class="far fa-chevron-double-right"></i> Digital
                                    Marketing </a>
                            </li>
                            <li>
                                <a href="service-details.html"> <i class="far fa-chevron-double-right"></i> App
                                    Development </a>
                            </li>
                            <li>
                                <a href="service-details.html"> <i class="far fa-chevron-double-right"></i> Investment
                                    Advisory </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="footer__three-widget">
                    <h5>Contact Info</h5>
                    <div class="footer__three-widget-contact-info">
                        <div class="footer__three-widget-contact-info-item">
                            <div class="footer__three-widget-contact-info-item-txt"> <span>Email</span> <a
                                    href="mialto:uma1960@gmail.com">uma1960@gmail.com</a> </div>
                        </div>
                        <div class="footer__three-widget-contact-info-item">
                            <div class="footer__three-widget-contact-info-item-txt"> <span>Phone</span>
                                <a href="tel:+125(895)658568">+256 (0) 706 744 927</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright__one three">
            <div class="container">
                <div class="row justify-content-between copyright__one-container-area">
                    <div class="col-xl-5 col-lg-6">
                        <div class="copyright__one-left">
                            <p>© Copyright 2025 - All Rights Reserved By <br />
                                <a href="#" target="_blank">Uganda Medical Association - UMA</a>
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-6">
                        <div class="copyright__one-right"> <a href="#">Trams & Condition</a> <a
                                href="#">Privacy Policy</a> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Three Area End -->
    <!-- Scroll Btn Start -->
    <div class="scroll-up style-three">
        <svg class="scroll-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!-- Scroll Btn End -->
    <!-- Main JS -->
    <script src="{{ asset('web-pages/assets/js/jquery-3.7.1.min.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('web-pages/assets/js/bootstrap.min.js') }}"></script>
    <!-- Counter Up JS -->
    <script src="{{ asset('web-pages/assets/js/jquery.counterup.min.js') }}"></script>
    <!-- Popper JS -->
    <script src="{{ asset('web-pages/assets/js/popper.min.js') }}"></script>
    <!-- Progressbar JS -->
    <script src="{{ asset('web-pages/assets/js/progressbar.min.js') }}"></script>
    <!-- Magnific Popup JS -->
    <script src="{{ asset('web-pages/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <!-- Swiper Bundle JS -->
    <script src="{{ asset('web-pages/assets/js/swiper-bundle.min.js') }}"></script>
    <!-- Slick JS -->
    <script src="{{ asset('web-pages/assets/js/slick.min.js') }}"></script>
    <!-- Isotope JS -->
    <script src="{{ asset('web-pages/assets/js/isotope.pkgd.min.js') }}"></script>
    <!-- Fancy Box -->
    <script src="{{ asset('web-pages/assets/js/jquery.fancybox.min.js') }}"></script>
    <!-- Waypoints JS -->
    <script src="{{ asset('web-pages/assets/js/jquery.waypoints.min.js') }}"></script>
    <!-- Custom JS -->
    <script src="{{ asset('web-pages/assets/js/custom.js') }}"></script>
</body>

</html>
