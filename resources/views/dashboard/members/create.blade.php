@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Create New Member') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-{{ session('status')['type'] }}">
                                {!! session('status')['message'] !!}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="alert alert-info">
                            <p><strong>Note:</strong> As an admin, you only need to fill in the required fields marked with
                                an asterisk (*). The member will be able to complete the remaining fields when they update
                                their profile.</p>
                            <p>An automatically generated UMA number will be assigned to this member when you submit the
                                form.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.members.store') }}" enctype="multipart/form-data">
                            @csrf

                            selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                            </select>
                            @error('membership_category_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                </div>

                <div class="row mb-3" id="specialization-container" style="display: none;">
                    <div class="col-md-12">
                        <label for="specialization" class="form-label">{{ __('Specialization') }}</label>
                        <input id="specialization" type="text"
                            class="form-control @error('specialization') is-invalid @enderror" name="specialization"
                            value="{{ old('specialization') }}">
                        @error('specialization')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3" id="umdpc-container" style="display: none;">
                    <div class="col-md-12">
                        <label for="umdpc_number" class="form-label">{{ __('UMDPC Number') }}</label>
                        <input id="umdpc_number" type="text"
                            class="form-control @error('umdpc_number') is-invalid @enderror" name="umdpc_number"
                            value="{{ old('umdpc_number') }}">
                        @error('umdpc_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="referee" class="form-label">{{ __('Referee') }}</label>
                        <input id="referee" type="text" class="form-control @error('referee') is-invalid @enderror"
                            name="referee" value="{{ old('referee') }}">
                        @error('referee')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="referee_phone1" class="form-label">{{ __('Referee Phone 1') }}</label>
                        <input id="referee_phone1" type="text"
                            class="form-control @error('referee_phone1') is-invalid @enderror" name="referee_phone1"
                            value="{{ old('referee_phone1') }}">
                        @error('referee_phone1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="referee_phone2" class="form-label">{{ __('Referee Phone 2') }}</label>
                        <input id="referee_phone2" type="text"
                            class="form-control @error('referee_phone2') is-invalid @enderror" name="referee_phone2"
                            value="{{ old('referee_phone2') }}">
                        @error('referee_phone2')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <hr>
                <h4>Documents</h4>
                <div class="alert alert-info">
                    <p>All document uploads are optional for admin registration. Members can upload these documents later.
                    </p>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="photo" class="form-label">{{ __('Photo') }}</label>
                        <input id="photo" type="file" class="form-control @error('photo') is-invalid @enderror"
                            name="photo">
                        <small class="text-muted">Accepted formats: jpg, jpeg, png, pdf, doc, docx (Max: 10MB)</small>
                        @error('photo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="signature" class="form-label">{{ __('Signature') }}</label>
                        <input id="signature" type="file" class="form-control @error('signature') is-invalid @enderror"
                            name="signature">
                        <small class="text-muted">Accepted formats: jpg, jpeg, png, pdf, doc, docx (Max: 10MB)</small>
                        @error('signature')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="national_id" class="form-label">{{ __('National ID') }}</label>
                        <input id="national_id" type="file"
                            class="form-control @error('national_id') is-invalid @enderror" name="national_id">
                        <small class="text-muted">Accepted formats: jpg, jpeg, png, pdf, doc, docx (Max: 10MB)</small>
                        @error('national_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6" id="license-container" style="display: none;">
                        <label for="license_document" class="form-label">{{ __('License Document') }}</label>
                        <input id="license_document" type="file"
                            class="form-control @error('license_document') is-invalid @enderror" name="license_document">
                        <small class="text-muted">Accepted formats: jpg, jpeg, png, pdf, doc, docx (Max: 10MB)</small>
                        @error('license_document')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <hr>
                <h4>Payment Information</h4>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="payment_mode" class="form-label">{{ __('Payment Mode') }}</label>
                        <select id="payment_mode" class="form-select @error('payment_mode') is-invalid @enderror"
                            name="payment_mode">
                            <option value="">Select Payment Mode</option>
                            @foreach ($payment_methods as $method)
                                <option value="{{ $method->id }}"
                                    {{ old('payment_mode') == $method->id ? 'selected' : '' }}>{{ $method->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_mode')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6" id="payment-phone-container" style="display: none;">
                        <label for="payment_phone" class="form-label">{{ __('Mobile Money Number') }}</label>
                        <input id="payment_phone" type="text"
                            class="form-control @error('payment_phone') is-invalid @enderror" name="payment_phone"
                            value="{{ old('payment_phone') }}">
                        @error('payment_phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Create Member') }}
                        </button>
                    </div>
                </div></strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <label for="referee" class="form-label">{{ __('Referee') }}</label>
            <input id="referee" type="text" class="form-control @error('referee') is-invalid @enderror"
                name="referee" value="{{ old('referee') }}" required>
            @error('referee')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="referee_phone1" class="form-label">{{ __('Referee Phone 1') }}</label>
            <input id="referee_phone1" type="text"
                class="form-control @error('referee_phone1') is-invalid @enderror" name="referee_phone1"
                value="{{ old('referee_phone1') }}" required>
            @error('referee_phone1')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="referee_phone2" class="form-label">{{ __('Referee Phone 2') }}</label>
            <input id="referee_phone2" type="text"
                class="form-control @error('referee_phone2') is-invalid @enderror" name="referee_phone2"
                value="{{ old('referee_phone2') }}">
            @error('referee_phone2')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <hr>
    <h4>Documents</h4>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="photo" class="form-label">{{ __('Photo') }}</label>
            <input id="photo" type="file" class="form-control @error('photo') is-invalid @enderror"
                name="photo">
            <small class="text-muted">Accepted formats: jpg, jpeg, png, pdf, doc, docx (Max: 10MB)</small>
            @error('photo')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="signature" class="form-label">{{ __('Signature') }}</label>
            <input id="signature" type="file" class="form-control @error('signature') is-invalid @enderror"
                name="signature">
            <small class="text-muted">Accepted formats: jpg, jpeg, png, pdf, doc, docx (Max: 10MB)</small>
            @error('signature')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="national_id" class="form-label">{{ __('National ID') }}</label>
            <input id="national_id" type="file" class="form-control @error('national_id') is-invalid @enderror"
                name="national_id" required>
            <small class="text-muted">Accepted formats: jpg, jpeg, png, pdf, doc, docx (Max: 10MB)</small>
            @error('national_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="col-md-6" id="license-container" style="display: none;">
            <label for="license_document" class="form-label">{{ __('License Document') }}</label>
            <input id="license_document" type="file"
                class="form-control @error('license_document') is-invalid @enderror" name="license_document">
            <small class="text-muted">Accepted formats: jpg, jpeg, png, pdf, doc, docx (Max: 10MB)</small>
            @error('license_document')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <hr>
    <h4>Payment Information</h4>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="payment_mode" class="form-label">{{ __('Payment Mode') }}</label>
            <select id="payment_mode" class="form-select @error('payment_mode') is-invalid @enderror"
                name="payment_mode" required>
                <option value="">Select Payment Mode</option>
                @foreach ($payment_methods as $method)
                    <option value="{{ $method->id }}" {{ old('payment_mode') == $method->id ? 'selected' : '' }}>
                        {{ $method->name }}</option>
                @endforeach
            </select>
            @error('payment_mode')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="col-md-6" id="payment-phone-container" style="display: none;">
            <label for="payment_phone" class="form-label">{{ __('Mobile Money Number') }}</label>
            <input id="payment_phone" type="text"
                class="form-control @error('payment_phone') is-invalid @enderror" name="payment_phone"
                value="{{ old('payment_phone') }}">
            @error('payment_phone')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mb-0">
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary">
                {{ __('Create Member') }}
            </button>
        </div>
    </div>
    </form>
</div>
</div>
</div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide specialization field based on category
        const categorySelect = document.getElementById('category');
        const specializationContainer = document.getElementById('specialization-container');
        const umdpcContainer = document.getElementById('umdpc-container');
        const licenseContainer = document.getElementById('license-container');

        // Show/hide payment phone field based on payment mode
        const paymentModeSelect = document.getElementById('payment_mode');
        const paymentPhoneContainer = document.getElementById('payment-phone-container');

        // Initialize the fields based on current values
        toggleSpecializationField();
        toggleUmdpcField();
        toggleLicenseField();
        togglePaymentPhoneField();

        // Set up event listeners
        categorySelect.addEventListener('change', function() {
            toggleSpecializationField();
            toggleUmdpcField();
            toggleLicenseField();
        });

        paymentModeSelect.addEventListener('change', togglePaymentPhoneField);

        function toggleSpecializationField() {
            if (categorySelect.value === 'Specialist') {
                specializationContainer.style.display = 'block';
            } else {
                specializationContainer.style.display = 'none';
            }
        }

        function toggleUmdpcField() {
            const requireUmdpc = ['Medical Student', 'Medical Officer', 'Specialist'].includes(categorySelect
                .value);
            if (requireUmdpc) {
                umdpcContainer.style.display = 'block';
            } else {
                umdpcContainer.style.display = 'none';
            }
        }

        function toggleLicenseField() {
            const requireLicense = ['Medical Student', 'Medical Officer', 'Specialist'].includes(categorySelect
                .value);
            if (requireLicense) {
                licenseContainer.style.display = 'block';
            } else {
                licenseContainer.style.display = 'none';
            }
        }

        function togglePaymentPhoneField() {
            // Find the selected payment method name
            const selectedOption = paymentModeSelect.options[paymentModeSelect.selectedIndex];
            const selectedPaymentMethodName = selectedOption ? selectedOption.textContent.trim() : '';

            if (selectedPaymentMethodName === 'Mobile Money') {
                paymentPhoneContainer.style.display = 'block';
            } else {
                paymentPhoneContainer.style.display = 'none';
            }
        }
    });
</script>
@endsection
