@extends('layouts.guest')
@section('content')
    <style>
        .summary-section {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 1rem;
        }

        .summary-section:last-child {
            border-bottom: none;
        }

        .edit-section {
            font-size: 0.8rem;
        }

        .text-success {
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }
    </style>

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
                                            Submit Details</a>
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
                                                <input type="file" name="license_document"
                                                    class="form-control license-field">
                                                <small class="text-muted">Required for registered doctors/medical students.
                                                    Max 10 MB.</small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-secondary prevBtn">Previous</button>
                                        <button type="button" class="btn btn-primary nextBtn">Next</button>
                                    </div>

                                    <!-- Step 5: Conclude -->
                                    <div class="tab-pane fade" id="step5">
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-0">Registration Summary</h5>
                                                <p class="text-muted mb-0">Please review your information before submission
                                                </p>
                                            </div>
                                            <div class="card-body">
                                                <!-- Summary Sections -->
                                                <div class="summary-section mb-4">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h6 class="mb-0 fw-bold">Personal Information</h6>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary edit-section"
                                                            data-step="step1">Edit</button>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>Name:</strong> <span
                                                                    id="summary-name"></span></p>
                                                            <p class="mb-1"><strong>Email:</strong> <span
                                                                    id="summary-email"></span></p>
                                                            <p class="mb-1"><strong>Phone:</strong> <span
                                                                    id="summary-phone"></span></p>
                                                            <p class="mb-1"><strong>Age:</strong> <span
                                                                    id="summary-age"></span></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>Address:</strong> <span
                                                                    id="summary-address"></span></p>
                                                            <p class="mb-1"><strong>Gender:</strong> <span
                                                                    id="summary-gender"></span></p>
                                                            <p class="mb-1"><strong>Marital Status:</strong> <span
                                                                    id="summary-marital"></span></p>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-12">
                                                            <p class="mb-1"><strong>Next of Kin:</strong> <span
                                                                    id="summary-kin"></span></p>
                                                            <p class="mb-1"><strong>Next of Kin Phone:</strong> <span
                                                                    id="summary-kin-phone"></span></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="summary-section mb-4">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h6 class="mb-0 fw-bold">Employment Information</h6>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary edit-section"
                                                            data-step="step2">Edit</button>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>UMA Branch:</strong> <span
                                                                    id="summary-branch"></span></p>
                                                            <p class="mb-1"><strong>Employer:</strong> <span
                                                                    id="summary-employer"></span></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>Category:</strong> <span
                                                                    id="summary-category"></span></p>
                                                            <p class="mb-1"><strong>UMDPC Number:</strong> <span
                                                                    id="summary-umdpc"></span></p>
                                                            <p class="mb-1"><strong>Specialization:</strong> <span
                                                                    id="summary-specialization"></span></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="summary-section mb-4">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h6 class="mb-0 fw-bold">Membership Information</h6>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary edit-section"
                                                            data-step="step3">Edit</button>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>Membership Type:</strong> <span
                                                                    id="summary-membership"></span></p>
                                                            <p class="mb-1"><strong>Referee:</strong> <span
                                                                    id="summary-referee"></span></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>Referee Phone 1:</strong> <span
                                                                    id="summary-referee-phone1"></span></p>
                                                            <p class="mb-1"><strong>Referee Phone 2:</strong> <span
                                                                    id="summary-referee-phone2"></span></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="summary-section mb-4">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h6 class="mb-0 fw-bold">Documents</h6>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary edit-section"
                                                            data-step="step4">Edit</button>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>Photo:</strong> <span
                                                                    id="summary-photo">Not Uploaded</span></p>
                                                            <p class="mb-1"><strong>Signature:</strong> <span
                                                                    id="summary-signature">Not Uploaded</span></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>National ID:</strong> <span
                                                                    id="summary-national-id">Not Uploaded</span></p>
                                                            <p class="mb-1"><strong>License Document:</strong> <span
                                                                    id="summary-license">Not Uploaded</span></p>
                                                        </div>
                                                    </div>
                                                    <p class="mb-1"><strong>UMA Number:</strong> <span
                                                            id="summary-uma-number">Will be generated after
                                                            submission</span></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Please review all information carefully before submitting. You can click the
                                            "Edit" button in each section to make changes.
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
                if (value === 'Medical Student' || value === 'Medical Officer' || value === 'Specialist') {
                    umdpcField.classList.add('required');
                    licenseField.classList.add('required');
                } else {
                    umdpcField.classList.remove('required');
                    licenseField.classList.remove('required');
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

                        // If moving to step5, populate the summary
                        if (nextTab.getAttribute("href") === "#step5") {
                            populateSummary();
                        }
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

            // Handle tab clicks directly
            document.querySelectorAll(".nav-link").forEach(navLink => {
                navLink.addEventListener("click", function(e) {
                    // Prevent default behavior to ensure our custom logic runs
                    e.preventDefault();

                    const targetTabId = this.getAttribute("href").substring(1);

                    // If navigating to summary step, populate it
                    if (targetTabId === "step5") {
                        populateSummary();
                    }

                    // Manually activate the tab
                    new bootstrap.Tab(this).show();
                });
            });

            // Function to populate the summary
            function populateSummary() {
                // Personal Information
                document.getElementById("summary-name").textContent =
                    `${document.querySelector('input[name="first_name"]').value} ${document.querySelector('input[name="last_name"]').value}`;
                document.getElementById("summary-email").textContent = document.querySelector('input[name="email"]')
                    .value;
                document.getElementById("summary-phone").textContent = document.querySelector('input[name="phone"]')
                    .value;
                document.getElementById("summary-age").textContent = document.querySelector('input[name="age"]')
                    .value;
                document.getElementById("summary-address").textContent = document.querySelector(
                    'input[name="address"]').value;
                document.getElementById("summary-gender").textContent = document.querySelector(
                    'select[name="gender"]').value;
                document.getElementById("summary-marital").textContent = document.querySelector(
                    'select[name="marital_status"]').value;
                document.getElementById("summary-kin").textContent = document.querySelector(
                    'input[name="next_of_kin"]').value;
                document.getElementById("summary-kin-phone").textContent = document.querySelector(
                    'input[name="next_of_kin_phone"]').value;

                // Employment Information
                document.getElementById("summary-branch").textContent = document.querySelector(
                    'select[name="uma_branch"]').value;
                document.getElementById("summary-employer").textContent = document.querySelector(
                    'input[name="employer"]').value;
                document.getElementById("summary-category").textContent = document.querySelector(
                    'select[name="category"]').value;
                document.getElementById("summary-umdpc").textContent = document.querySelector(
                    'input[name="umdpc_number"]').value || "N/A";
                document.getElementById("summary-specialization").textContent = document.querySelector(
                    'input[name="specialization"]').value || "N/A";

                // Membership Information
                const membershipSelect = document.querySelector('select[name="membership_category_id"]');
                const membershipText = membershipSelect.options[membershipSelect.selectedIndex]?.text ||
                    "Not Selected";
                document.getElementById("summary-membership").textContent = membershipText;
                document.getElementById("summary-referee").textContent = document.querySelector(
                    'input[name="referee"]').value;
                document.getElementById("summary-referee-phone1").textContent = document.querySelector(
                    'input[name="referee_phone1"]').value;
                document.getElementById("summary-referee-phone2").textContent = document.querySelector(
                    'input[name="referee_phone2"]').value || "N/A";
                    
                // Documents
                updateFileStatus('photo', 'summary-photo');
                updateFileStatus('signature', 'summary-signature');
                updateFileStatus('national_id', 'summary-national-id');
                updateFileStatus('license_document', 'summary-license');
            }

            document.getElementById("summary-uma-number").textContent =
                    document.querySelector('input[name="uma_number"]').value ||
                    "Will be generated after submission";

            // Function to show uploaded file status
            function updateFileStatus(inputName, summaryId) {
                const fileInput = document.querySelector(`input[name="${inputName}"]`);
                const summaryElement = document.getElementById(summaryId);

                if (fileInput.files && fileInput.files[0]) {
                    summaryElement.textContent = `Uploaded: ${fileInput.files[0].name}`;
                    summaryElement.classList.add('text-success');
                } else {
                    summaryElement.textContent = 'Not Uploaded';
                    if (fileInput.classList.contains('required')) {
                        summaryElement.classList.add('text-danger');
                    }
                }
            }

            // Add click event for "Edit" buttons to navigate to the corresponding step
            document.querySelectorAll('.edit-section').forEach(button => {
                button.addEventListener('click', function() {
                    const stepToShow = this.dataset.step;
                    const tabElement = document.querySelector(`.nav-link[href="#${stepToShow}"]`);
                    // Use Bootstrap's Tab API to show the tab
                    const bsTab = new bootstrap.Tab(tabElement);
                    bsTab.show();
                });
            });

            // Initialize: Hide conditional fields
            specializationField.parentElement.style.display = 'none';
            if (window.paymentPhone) {
                window.paymentPhone.parentElement.style.display = 'none';
            }
        });
    </script>
@endsection
