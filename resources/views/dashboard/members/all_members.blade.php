@extends('layouts.dash')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <h1 class="h3 mb-3"><strong>All Members</strong></h1>

    <div class="row mb-3">
        <div class="col-md-8">
            <a href="#" class="btn btn-primary">Add New Member</a>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-info fs-6">Total: {{ $users->total() }}</span>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card mb-3">
        <div class="card-body">
            <form id="search-form" action="{{ route('members.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" id="search-input" name="search" class="form-control"
                            placeholder="Search by name, email, or UMA number" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select" id="category-filter">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="branch" class="form-select" id="branch-filter">
                        <option value="">All Branches</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('members.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"> No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>UMA Number</th>
                            <th>Employer</th>
                            <th>Branch</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="members-table-body">
                        @foreach ($users as $index => $user)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_ids[]" value="{{ $user->id }}"
                                        class="order-select">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                                </td>
                                <td>{{ $user->first_name }}
                                    <small>{{ $user->last_name }}</small>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->profile ? $user->profile->phone : 'N/A' }}</td>
                                <td>{{ $user->profile ? $user->profile->uma_number : 'N/A' }}</td>
                                <td>{{ $user->profile ? $user->profile->employer : 'N/A' }}</td>
                                <td>{{ $user->profile && $user->profile->branch ? $user->profile->branch->name : 'N/A' }}
                                </td>
                                <td>
                                    @if ($user->profile && $user->profile->membershipCategory)
                                        <span
                                            class="badge bg-{{ $user->profile->membershipCategory->name == 'Medical Student'
                                                ? 'info'
                                                : ($user->profile->membershipCategory->name == 'Intern Doctor'
                                                    ? 'warning'
                                                    : ($user->profile->membershipCategory->name == 'Medical Officer'
                                                        ? 'primary'
                                                        : ($user->profile->membershipCategory->name == 'Specialist'
                                                            ? 'success'
                                                            : 'secondary'))) }}">{{ $user->profile->membershipCategory->name }}</span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" id="dropdownMenuButton{{ $user->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            &#8942; <!-- HTML entity for vertical ellipsis -->
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end"
                                            aria-labelledby="dropdownMenuButton{{ $user->id }}">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('members.show', $user->id) }}">
                                                    <i class="fas fa-eye me-2"></i> View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fas fa-edit me-2"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger delete-member" href="javascript:void(0)"
                                                    data-id="{{ $user->id }}"
                                                    data-name="{{ $user->first_name }} {{ $user->last_name }}">
                                                    <i class="fas fa-trash me-2"></i> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <div>
                <select id="per-page" class="form-select form-select-sm" style="width: auto;">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per page</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                </select>
            </div>
            <div>
                {{ $users->links('vendor.pagination.simple-tailwind') }}
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <span id="member-name"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="delete-form" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Delete confirmation modal
        const deleteButtons = document.querySelectorAll('.delete-member');
        const deleteForm = document.getElementById('delete-form');
        const memberNameSpan = document.getElementById('member-name');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const memberId = this.getAttribute('data-id');
                const memberName = this.getAttribute('data-name');

                deleteForm.action = `/members/${memberId}`;
                memberNameSpan.textContent = memberName;

                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Real-time search functionality
            const searchInput = document.getElementById('search-input');
            const categoryFilter = document.getElementById('category-filter');
            const branchFilter = document.getElementById('branch-filter');
            const perPageSelect = document.getElementById('per-page');
            let typingTimer;
            const doneTypingInterval = 500; // ms

            // Handle search input with debounce
            searchInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(submitSearchForm, doneTypingInterval);
            });

            searchInput.addEventListener('keydown', function() {
                clearTimeout(typingTimer);
            });

            // Auto-submit form when filters change
            categoryFilter.addEventListener('change', submitSearchForm);
            branchFilter.addEventListener('change', submitSearchForm);
            perPageSelect.addEventListener('change', function() {
                const url = new URL(window.location);
                url.searchParams.set('per_page', this.value);
                window.location.href = url.toString();
            });

            function submitSearchForm() {
                document.getElementById('search-form').submit();
            }

            // Select all checkbox
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.order-select');

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            });

            // Delete confirmation modal
            const deleteButtons = document.querySelectorAll('.delete-member');
            const deleteForm = document.getElementById('delete-form');
            const memberNameSpan = document.getElementById('member-name');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const memberId = this.getAttribute('data-id');
                    const memberName = this.getAttribute('data-name');

                    deleteForm.action = `/members/${memberId}`;
                    memberNameSpan.textContent = memberName;

                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    deleteModal.show();
                });
            });
        });
    </script>
@endsection
