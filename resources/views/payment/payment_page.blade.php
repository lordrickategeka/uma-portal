@extends('layouts.dash')

@section('content')
    <div class="container d-flex flex-column flex-md-row justify-content-center align-items-start" style="min-height: 80vh;">
        <!-- Card for Payment -->
        <div class="card w-100 w-md-50 mb-4 mb-md-0" style="position: sticky; top: 20px; z-index: 100;">
            <div class="card-body">
                <h1 class="h3 mb-3"><strong>Payment for {{ $plan->name }}</strong></h1>
                <hr />
                <!-- Displaying User Information -->
                <h5 class="mb-3"><strong>User Information</strong></h5>
                <p><strong>Name:</strong> {{ Auth::user()->first_name }}</p>
                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                <p><strong>Phone:</strong> {{ Auth::user()->profile->phone ?? 'N/A' }}</p>
                <p><strong>Payment Method:</strong>
                    {{ Auth::user()->UserPaymentMethods->first()->paymentMethod->name ?? 'N/A' }}</p>
                <p><strong>Account Number:</strong> {{ Auth::user()->UserPaymentMethods->first()->account_number ?? 'N/A' }}
                </p>
                <hr />
                <!-- Displaying Selected Plan Information -->
                <h5 class="mb-3"><strong>Plan Information</strong></h5>
                <p><strong>Plan:</strong> {{ $plan->name }}</p>
                <p><strong>Amount:</strong> UGX {{ number_format($plan->price, 0) }}</p>
                <hr />
                <!-- Displaying Membership Category Information -->
                <h5 class="mb-3"><strong>Membership Category</strong></h5>
                <p><strong>Category:</strong> {{ $plan->membershipCategory->name ?? 'Not Selected' }}</p>
                <!-- Assuming 'category' is a relationship -->
                <p><strong>Description:</strong> {{ $plan->membershipCategory->description ?? 'No description available' }}
                </p>

                <!-- Pay with pesapal-->
                <form action="{{ route('payment.subscription') }}" method="POST">
                    @csrf
                    @method('post')
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <button type="submit" class="btn btn-success">Proceed to Payment (pesapal)</button>
                </form>

                {{-- pay with flutterwave --}}
                <form action="{{ route('payments.initialize') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                    @if (isset($installment_option) && $installment_option)
                        <input type="hidden" name="installment_option" value="{{ $installment_option }}">
                    @endif

                    <button type="submit" class="mt-4 btn btn-info">Proceed to Payment (flutterwave)</button>
                </form>
            </div>
        </div>


        <!-- Card for Membership Benefits -->
        <div class="card w-100 w-md-50">
            <div class="card-body">
                <h1 class="h3 mb-3"><strong>Membership Benefits</strong></h1>
                <p>UMA offers a wide range of benefits to its members, including:</p>
                <ol>
                    <li><strong>Continuous Professional Development (CPD) Programs</strong> – Gain access to structured
                        learning and training opportunities.</li><br />
                    <li><strong>Representation on statutory and professional bodies</strong> – Ensure your voice is heard in
                        key policy and regulatory discussions.</li><br />
                    <li><strong>Access to financial support through UMA SACCO</strong> – Benefit from affordable financial
                        facilities tailored for medical professionals.</li><br />
                    <li><strong>Group Indemnity Insurance</strong> – Secure professional liability coverage for medical
                        practitioners.</li><br />
                    <li><strong>Labour Relations Support</strong> – Receive expert guidance on employment matters in both
                        public and private healthcare sectors.</li><br />
                    <li><strong>Legal Aid Clinic for Doctors</strong> – Access legal support and advisory services tailored
                        to the medical profession.</li><br />
                    <li><strong>Investment Opportunities</strong> – Participate in joint investment initiatives designed for
                        doctors.</li><br />
                    <li><strong>Exclusive Access to Medical Publications</strong> – Enjoy free access to the Uganda Medical
                        Journal and the UMA Newsletter.</li><br />
                    <li><strong>Research Publication Platform</strong> – Publish approved research articles at no cost.</li>
                </ol>
            </div>
        </div>
    </div>
@endsection
