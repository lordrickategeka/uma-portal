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
    <h1 class="h3 mb-3"><strong>All Transactions</strong></h1>
    <div class="row mb-3">
        <div class="col-md-8">
            <a href="#" class="btn btn-success mb-3">Export Data</a>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-info fs-6">Total: {{ $transactions->total() }}</span>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <form id="filter-form" action="{{ route('transactions.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search member or reference"
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="payment_method" class="form-select">
                        <option value="">All Payment Methods</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="mobile_money" {{ request('payment_method') == 'mobile_money' ? 'selected' : '' }}>
                            Mobile Money</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                            Bank Transfer</option>
                        <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>
                            Credit Card</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="plan_id" class="form-select">
                        <option value="">All Plans</option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
                <div class="col-md-1">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle w-100" type="button" id="exportDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Export
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                            <li><a class="dropdown-item"
                                    href="{{ route('transactions.export', request()->all() + ['format' => 'csv']) }}">CSV</a>
                            </li>
                            <li><a class="dropdown-item"
                                    href="{{ route('transactions.export', request()->all() + ['format' => 'pdf']) }}">PDF</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add this near the top of your table, perhaps next to the export dropdown -->
<div class="dropdown d-none" id="bulk-actions">
    <button class="btn btn-primary dropdown-toggle" type="button" id="bulkActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        Bulk Actions (<span id="selected-count">0</span>)
    </button>
    <ul class="dropdown-menu" aria-labelledby="bulkActionsDropdown">
        <li><a class="dropdown-item" href="#" id="bulk-export-csv">Export Selected as CSV</a></li>
        <li><a class="dropdown-item" href="#" id="bulk-export-pdf">Export Selected as PDF</a></li>
        @if(Auth::user()->can('approve-transactions'))
        <li><a class="dropdown-item" href="#" id="bulk-approve">Approve Selected</a></li>
        @endif
        @if(Auth::user()->can('delete-transactions'))
        <li><a class="dropdown-item text-danger" href="#" id="bulk-delete">Delete Selected</a></li>
        @endif
    </ul>
</div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Transactions</h5>
            <span class="badge bg-primary">{{ $transactions->total() }} Records</span>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"> No.</th>
                    <th>UMA Number</th>
                    <th>Member</th>
                    <th>Email / Phone</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $index => $transaction)
                    <tr class="border-t">
                        <td>
                            <input type="checkbox" class="transaction-select" data-id="{{ $transaction->id }}">
                            {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $index + 1 }}
                        </td>
                        <td>{{ $transaction->uma_number ?? 'N/A' }}</td>
                        <td>{{ $transaction->first_name }} {{ $transaction->last_name }}</td>
                        <td>
                            {{ $transaction->email }}<br>
                            <small class="text-muted">{{ $transaction->phone ?? 'No phone' }}</small>
                        </td>
                        <td>{{ $transaction->plan_name ?? 'N/A' }}</td>
                        <td>{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</td>
                        <td>{{ $transaction->payment_method }}</td>
                        <td>
                            <span
                                class="badge {{ $transaction->status == 'paid' ? 'bg-success' : ($transaction->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button"
                                    id="dropdownMenuButton{{ $transaction->id }}" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    &#8942; <!-- HTML entity for vertical ellipsis -->
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="dropdownMenuButton{{ $transaction->id }}">
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('transactions.show', ['transaction' => $transaction->id]) }}">
                                            <i class="fas fa-eye me-2"></i> View
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-edit me-2"></i> Edit
                                        </a>
                                    </li>
                                    @if ($transaction->status == 'pending' && Auth::user()->can('approve-transactions'))
                                        <li>
                                            <a class="dropdown-item text-success"
                                                href="{{ route('transactions.approve', $transaction->id) }}">
                                                <i class="fas fa-check me-2"></i> Approve
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::user()->can('delete-transactions'))
                                        <li>
                                            <a class="dropdown-item text-danger delete-transaction"
                                                href="javascript:void(0)" data-id="{{ $transaction->id }}">
                                                <i class="fas fa-trash me-2"></i> Delete
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

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
                {{ $transactions->links('vendor.pagination.simple-tailwind') }}
            </div>
        </div>
    </div>

    <div class="card-footer">
        {{ $transactions->links('vendor.pagination.simple-tailwind') }}
    </div>
    </div>
    <script>
        // Select all checkbox functionality
        document.getElementById('select-all').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.transaction-select');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Per page selector
            const perPageSelect = document.getElementById('per-page');
            perPageSelect.addEventListener('change', function() {
                const url = new URL(window.location);
                url.searchParams.set('per_page', this.value);
                window.location.href = url.toString();
            });

            // Select all checkbox
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.transaction-select');

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            });

            // Delete confirmation modal setup
            const deleteButtons = document.querySelectorAll('.delete-transaction');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const transactionId = this.getAttribute('data-id');

                    if (confirm(
                            'Are you sure you want to delete this transaction? This action cannot be undone.'
                        )) {
                        // Create form element
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/transactions/${transactionId}`;
                        form.style.display = 'none';

                        // Add CSRF token
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        // Add method override for DELETE
                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';
                        form.appendChild(methodField);

                        // Append to body and submit
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        // bulk export
        // Handle bulk actions
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selected-count');
        const checkboxes = document.querySelectorAll('.transaction-select');
        const selectAll = document.getElementById('select-all');

        // Update selected count and show/hide bulk actions
        function updateSelectedCount() {
            const checkedBoxes = document.querySelectorAll('.transaction-select:checked');
            selectedCount.textContent = checkedBoxes.length;

            if (checkedBoxes.length > 0) {
                bulkActions.classList.remove('d-none');
            } else {
                bulkActions.classList.add('d-none');
            }
        }

        // Add event listeners to checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            updateSelectedCount();
        });

        // Bulk export handlers
        document.getElementById('bulk-export-csv').addEventListener('click', function(e) {
            e.preventDefault();
            bulkExport('csv');
        });

        document.getElementById('bulk-export-pdf').addEventListener('click', function(e) {
            e.preventDefault();
            bulkExport('pdf');
        });

        function bulkExport(format) {
            const selectedIds = Array.from(document.querySelectorAll('.transaction-select:checked'))
                .map(checkbox => checkbox.getAttribute('data-id'));

            if (selectedIds.length === 0) return;

            // Create a form to submit the selected IDs
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('transactions.export') }}";
            form.style.display = 'none';

            // Add the format parameter
            const formatInput = document.createElement('input');
            formatInput.type = 'hidden';
            formatInput.name = 'format';
            formatInput.value = format;
            form.appendChild(formatInput);

            // Add the selected IDs
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            // Append to body and submit
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endsection
