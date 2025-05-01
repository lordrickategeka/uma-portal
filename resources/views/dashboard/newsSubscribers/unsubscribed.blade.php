@extends('layouts.web-pages')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Unsubscribed Successfully</h2>
                </div>

                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h3 class="mb-3">You have been unsubscribed</h3>
                    
                    <p class="lead mb-4">
                        We're sorry to see you go! You have been successfully unsubscribed from our mailing list.
                    </p>
                    
                    <p class="mb-4">
                        You will no longer receive emails from us. If this was a mistake or you change your mind, 
                        you can always re-subscribe using the form below.
                    </p>
                    
                    <div class="mt-4">
                        {{-- <a href="{{ route('subscription.submit') }}" class="btn btn-primary me-2">
                            <i class="fas fa-envelope me-2"></i>Re-subscribe
                        </a> --}}
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            <i class="fas fa-home me-2"></i>Return to Home
                        </a>
                    </div>
                </div>
                
                <div class="card-footer text-center text-muted">
                    <small>If you have any questions, please <a href="{{ route('contact.page') }}">contact us</a>.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        margin-top: 50px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 1.5rem;
    }
    
    .card-body {
        padding: 2rem;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: 5px;
    }
</style>
@endpush