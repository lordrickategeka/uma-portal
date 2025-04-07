@extends('layouts.guest')
@section('content')
    <main class="main-content mt-0">
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white">
                            <h4 class="text-center">UMA Membership Registration</h4>
                        </div>
                        <div class="card-body">
                            <form id="registrationForm" action="{{ route('register') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <!-- Progress Bar -->
                                <ul class="nav nav-tabs mb-3" id="registrationSteps">
                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#step1">Step
                                            1:
                                            Personal Info</a></li>
                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#step2">Step 2:
                                            Employment</a></li>
                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#step3">Step 3:
                                            Membership</a></li>
                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#step4">Step 4:
                                            Documents</a></li>
                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#step5">Step 5:
                                            Payment</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <!-- Step 1: Personal Info -->
                                    <div class="tab-pane fade show active" id="step1">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label>First Name*</label>
                                                <input type="text" name="first_name" value="{{ old('first_name') }}"
                                                    class="form-control required">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Last Name*</label>
                                                <input type="text" name="last_name" value="{{ old('last_name') }}"
                                                    class="form-control required">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>Phone*</label>
                                                <input type="text" name="phone" value="{{ old('phone') }}"
                                                    class="form-control required">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>Age*</label>
                                                <input type="number" name="age" value="{{ old('age') }}"
                                                    class="form-control required">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>Email*</label>
                                                <input type="email" name="email" value="{{ old('email') }}"
                                                    class="form-control required">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Address*</label>
                                                <input type="text" name="address" value="{{ old('address') }}"
                                                    class="form-control required">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>Gender*</label>
                                                <select name="gender" class="form-select form-control">
                                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>
                                                        Male</option>
                                                    <option value="Female"
                                                        {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                                    <option value="Prefer not to say"
                                                        {{ old('gender') == 'Prefer not to say' ? 'selected' : '' }}>Prefer
                                                        not to say</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Marital Status*</label>
                                                <select name="marital_status" class="form-select form-control required">
                                                    <option value="">Select Status</option>
                                                    <option value="Married"
                                                        {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married
                                                    </option>
                                                    <option value="Single"
                                                        {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>Next of Kin (Relationship)*</label>
                                                <input type="text" name="next_of_kin" value="{{ old('next_of_kin') }}"
                                                    class="form-control required"
                                                    placeholder="Who is your N.O.K and what is your relationship?">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Next of Kin Telephone Contact*</label>
                                                <input type="text" name="next_of_kin_phone"
                                                    value="{{ old('next_of_kin_phone') }}" class="form-control required">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary nextBtn">Next</button>
                                    </div>

                                    <!-- Step 2: Employment -->
                                    <div class="tab-pane fade" id="step2">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>UMA Branch/Region*</label>
                                                <select name="uma_branch" class="form-select form-control required">
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->name }}"
                                                            {{ old('uma_branch') == $branch->name ? 'selected' : '' }}>
                                                            {{ $branch->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Name and Address of Employer/Business*</label>
                                                <input type="text" name="employer" value="{{ old('employer') }}"
                                                    class="form-control required">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>UMDPC Registration Number</label>
                                                <input type="text" name="umdpc_number"
                                                    value="{{ old('umdpc_number') }}" class="form-control umdpc-field"
                                                    placeholder="Required for all registered Doctors">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>Category*</label>
                                                <select name="category"
                                                    class="form-select form-control required category-select">
                                                    <option value="">Select Category</option>
                                                    <option value="Medical Student"
                                                        {{ old('category') == 'Medical Student' ? 'selected' : '' }}>
                                                        Medical Student</option>
                                                    <option value="Intern Doctor"
                                                        {{ old('category') == 'Intern Doctor' ? 'selected' : '' }}>Intern
                                                        Doctor</option>
                                                    <option value="Medical Officer"
                                                        {{ old('category') == 'Medical Officer' ? 'selected' : '' }}>
                                                        Medical Officer</option>
                                                    <option value="Specialist"
                                                        {{ old('category') == 'Specialist' ? 'selected' : '' }}>Specialist
                                                    </option>
                                                    <option value="Other"
                                                        {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Area of Specialization</label>
                                                <input type="text" name="specialization"
                                                    value="{{ old('specialization') }}"
                                                    class="form-control specialization-field">
                                            </div>

                                        </div>
                                        <button type="button" class="btn btn-secondary prevBtn">Previous</button>
                                        <button type="button" class="btn btn-primary nextBtn">Next</button>
                                    </div>

                                    <!-- Step 3: Membership -->
                                    <div class="tab-pane fade" id="step3">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>Membership Type*</label>
                                                <select name="membership_category_id" id="membership_category_id"
                                                    class="form-select form-control required">
                                                    <option value="">Select Membership Type</option>
                                                    @foreach ($membershipCategories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ old('membership_category_id') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Referee (Must be a registered UMA member)*</label>
                                                <input type="text" class="form-control required" name="referee"
                                                    value={{ old('referee') }}>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>1st Referee's Telephone Contact*</label>
                                                <input type="text" class="form-control required" name="referee_phone1"
                                                    value={{ old('referee_phone1') }}>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>2nd Referee's Telephone Contact</label>
                                                <input type="text" class="form-control" name="referee_phone2"
                                                    value={{ old('referee_phone2') }}>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-secondary prevBtn">Previous</button>
                                        <button type="button" class="btn btn-primary nextBtn">Next</button>
                                    </div>

                                    <!-- Step 4: Documents -->
                                    <div class="tab-pane fade" id="step4">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>Passport Size Photo</label>
                                                <input type="file" name="photo" class="form-control">
                                                <small class="text-muted">You must keep your head straight and your face
                                                    forward. Max 10 MB.</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Digital Signature (White background)</label>
                                                <input type="file" name="signature" class="form-control">
                                                <small class="text-muted">Must have a white background. Max 10 MB.</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>National ID*</label>
                                                <input type="file" name="national_id" class="form-control required">
                                                <small class="text-muted">Maximum file size is 10 MB.</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Annual Practicing License/Student Admission Letter</label>
                                                <input type="file" name="license_document" class="form-control license-field">
                                                <small class="text-muted">Required for registered doctors/medical students.
                                                    Max 10 MB.</small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-secondary prevBtn">Previous</button>
                                        <button type="button" class="btn btn-primary nextBtn">Next</button>
                                    </div>

                                    <!-- Step 5: Payment -->
                                    <div class="tab-pane fade" id="step5">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>Mode of Initial Payment*</label>
                                                <select name="payment_mode" class="form-select required">
                                                    <option value="">Select Payment Method</option>
                                                    <option value="Mobile Money"
                                                        {{ old('payment_method') == 'Mobile Money' ? 'selected' : '' }}>
                                                        Mobile Money</option>
                                                    <option value="Bank Transfer"
                                                        {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>
                                                        Bank Transfer</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Mobile Number Used to Send Money</label>
                                                <input type="text" name="payment_phone"
                                                    class="form-control payment-phone"
                                                    placeholder="For Mobile Money payments">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-secondary prevBtn">Previous</button>
                                        <button type="submit" class="btn btn-success">Submit Registration</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Form validation
            function validateStep(currentStep) {
                let isValid = true;
                document.querySelectorAll(`#${currentStep} .required`).forEach(function(input) {
                    if (!input.value.trim()) {
                        input.classList.add("is-invalid");
                        isValid = false;
                    } else {
                        input.classList.remove("is-invalid");
                    }
                });
                return isValid;
            }

            // Handle category selection to show/hide relevant fields
            const categorySelect = document.querySelector('.category-select');
            const specializationField = document.querySelector('.specialization-field');
            const umdpcField = document.querySelector('.umdpc-field');
            const licenseField = document.querySelector('.license-field');

            categorySelect.addEventListener('change', function() {
                const value = this.value;

                // Show specialization field for specialists
                if (value === 'Specialist') {
                    specializationField.classList.add('required');
                    specializationField.parentElement.style.display = 'block';
                } else {
                    specializationField.classList.remove('required');
                    specializationField.parentElement.style.display = value === 'Other' ? 'block' : 'none';
                }

                // Show UMDPC field for doctors
                if (value === 'Medical Student' ||value === 'Medical Officer' || value === 'Specialist' || value === 'Intern Doctor') {
                    umdpcField.classList.add('required');
                    licenseField.classList.add('required');
                } else {
                    umdpcField.classList.remove('required');
                    licenseField.classList.remove('required');
                }
            });

            // Handle payment method
            const paymentSelect = document.querySelector('select[name="payment_mode"]');
            const paymentPhone = document.querySelector('.payment-phone');

            paymentSelect.addEventListener('change', function() {
                if (this.value === 'Mobile Money') {
                    paymentPhone.classList.add('required');
                    paymentPhone.parentElement.style.display = 'block';
                } else {
                    paymentPhone.classList.remove('required');
                    paymentPhone.parentElement.style.display = 'none';
                }
            });

            // Navigation between tabs
            document.querySelectorAll(".nextBtn").forEach(button => {
                button.addEventListener("click", function() {
                    let currentStep = this.closest(".tab-pane").id;
                    let nextTab = document.querySelector(
                            `.nav-tabs .nav-link[href="#${currentStep}"]`).parentElement
                        .nextElementSibling?.querySelector(".nav-link");

                    if (validateStep(currentStep) && nextTab) {
                        new bootstrap.Tab(nextTab).show();
                    }
                });
            });

            document.querySelectorAll(".prevBtn").forEach(button => {
                button.addEventListener("click", function() {
                    let currentStep = this.closest(".tab-pane").id;
                    let prevTab = document.querySelector(
                            `.nav-tabs .nav-link[href="#${currentStep}"]`).parentElement
                        .previousElementSibling?.querySelector(".nav-link");

                    if (prevTab) {
                        new bootstrap.Tab(prevTab).show();
                    }
                });
            });

            // Initialize: Hide conditional fields
            specializationField.parentElement.style.display = 'none';
            paymentPhone.parentElement.style.display = 'none';
        });
    </script>
@endsection
