@extends('layouts.web-pages')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Unsubscribed</div>

                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-envelope fa-4x text-muted"></i>
                    </div>
                    <h3>Successfully Unsubscribed</h3>
                    <p class="lead mb-4">
                        You have been removed from our email notification list. You will no longer receive updates about new content.
                    </p>
                    <p>
                        Changed your mind? You can <a href="{{ route('subscription.form') }}">subscribe again</a> at any time.
                    </p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Return to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection