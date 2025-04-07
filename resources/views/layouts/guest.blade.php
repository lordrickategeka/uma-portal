<!-- /*
* Template Name: Tour
* Template Author: Untree.co
* Tempalte URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="favicon.png">

    <meta name="description" content="UMA Payment Portal for Uganda Medical Association members">
    <meta name="keywords" content="UMA Payment Portal, Uganda Medical Association, Medical Association">
    <meta name="robots" content="index, follow">
    <meta name="keywords" content="bootstrap, bootstrap4" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Source+Serif+Pro:wght@400;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('guesttheme/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('guesttheme/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('guesttheme/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('guesttheme/css/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('guesttheme/fonts/icomoon/style.css') }}">
    <link rel="stylesheet" href="{{ asset('guesttheme/fonts/flaticon/font/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('guesttheme/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('guesttheme/css/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('guesttheme/css/style.css') }}">

    <title>UMA Payment Portal</title>
</head>

<body>
    <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close">
                <span class="icofont-close js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>

    <nav class="site-nav">
        <div class="container">
            <div class="site-navigation">
                {{-- <a href="#" class="logo m-0">-UMA PP - <span class="text-primary">.</span></a> --}}
                <ul class="js-clone-nav d-none d-lg-inline-block text-left site-menu float-left">
                    <li class="active"><a href="/">Home</a></li>

                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
                <a href="#"
                    class="burger ml-auto float-right site-menu-toggle js-menu-toggle d-inline-block d-lg-none light"
                    data-toggle="collapse" data-target="#main-navbar">
                    <span></span>
                </a>

                <ul class="js-clone-nav d-none d-lg-inline-block text-left site-menu float-right">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">{{ __('Dashboard') }}</a>
                        </li>
                        <li class="has-children">
                            <a href="#">{{ Auth::user()->name }}</a>
                            <ul class="dropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>

            </div>
        </div>
    </nav>

    <div class="hero">
        <div class="container">
            <div class="row mb-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <h3 class="mb-5"><span class="d-block"> Welcome to the Uganda Medical Association Payment
                            Portal</span><span class="typed-words"></span></h3>
                </div>
            </div>
        </div>
    </div>

    @yield('content')

    <div id="overlayer"></div>
    <div class="loader">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <script src="{{ asset('guesttheme/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('guesttheme/js/popper.min.js') }}"></script>
    <script src="{{ asset('guesttheme/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('guesttheme/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('guesttheme/js/jquery.animateNumber.min.js') }}"></script>
    <script src="{{ asset('guesttheme/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('guesttheme/js/jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('guesttheme/js/aos.js') }}"></script>
    <script src="{{ asset('guesttheme/js/moment.min.js') }}"></script>
    <script src="{{ asset('guesttheme/js/daterangepicker.js') }}"></script>

    <script src="{{ asset('guesttheme/js/typed.js') }}"></script>
    <script>
        $(function() {
            var slides = $('.slides'),
                images = slides.find('img');

            images.each(function(i) {
                $(this).attr('data-id', i + 1);
            })

            var typed = new Typed('.typed-words', {
                strings: ["-UMA PP"],
                typeSpeed: 80,
                backSpeed: 80,
                backDelay: 4000,
                startDelay: 1000,
                loop: true,
                showCursor: true,
                preStringTyped: (arrayPos, self) => {
                    arrayPos++;
                    console.log(arrayPos);
                    $('.slides img').removeClass('active');
                    $('.slides img[data-id="' + arrayPos + '"]').addClass('active');
                }

            });
        })
    </script>

    <script src="{{ asset('guesttheme/js/custom.js') }}"></script>

</body>

</html>
