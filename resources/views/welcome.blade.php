@extends('layouts.guest')
@section('content')
    <div class="untree_co-section">
        <div class="container">
            <div class="row align-items-stretch justify-content-center">
                <div class="col-6 col-sm-6 col-lg-4 feature-1-wrap d-md-flex flex-md-column order-lg-1">
                    <div class="feature-1 d-md-flex">
                        <div class="align-self-center">
                            <a href="{{ url('login') }}">
                                <span class="flaticon-house display-4 text-primary"></span>
                                <h3>Existing Members</h3>
                                <p class="mb-0">UMA member that already registered with the portal.</p>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-sm-6 col-lg-4 feature-1-wrap d-md-flex flex-md-column order-lg-3">
                    <div class="feature-1 d-md-flex">
                        <div class="align-self-center">
                            <a href="{{ url('register') }}">
                                <span class="flaticon-mail display-4 text-primary"></span>
                                <h3>Get An Account</h3>
                                <p class="mb-0">For persons/instituations that have never registered on the portal.</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="untree_co-section count-numbers py-2">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                    <div class="counter-wrap">
                        <div class="counter">
                            <span class="" data-number="13">0</span>
                        </div>
                        <span class="caption">No. of Members</span>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                    <div class="counter-wrap">
                        <div class="counter">
                            <span class="" data-number="20">0</span>
                        </div>
                        <span class="caption">No. of Instituations</span>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                    <div class="counter-wrap">
                        <div class="counter">
                            <span class="" data-number="10">0</span>
                        </div>
                        <span class="caption">No. of Employees</span>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                    <div class="counter-wrap">
                        <div class="counter">
                            <span class="" data-number="12">0</span>
                        </div>
                        <span class="caption">No. of Branches</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
