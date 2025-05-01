@extends('layouts.dash')

@section('content')
    <div class="row">
        <!-- Left Column: Form for creating subaccount -->
        <div class="col-lg-6">
            <h1 class="h3 mb-3"><strong>Accounts</strong></h1>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"> Accounts</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('subaccounts.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="business_name">Business Name</label>
                            <input type="text" name="business_name" id="business_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="business_email">Business Email</label>
                            <input type="email" name="business_email" id="business_email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="account_bank">Account Bank</label>
                            <input type="text" name="account_bank" id="account_bank" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="account_number">Account Number</label>
                            <input type="text" name="account_number" id="account_number" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="country">Country (ISO code)</label>
                            <input type="text" name="country" id="country" class="form-control" required
                                maxlength="2">
                        </div>

                        <div class="form-group">
                            <label for="split_value">Split Value (0-1 for Percentage or Amount)</label>
                            <input type="number" name="split_value" id="split_value" class="form-control" required
                                step="0.01" min="0">
                        </div>

                        <div class="form-group">
                            <label for="split_type">Split Type</label>
                            <select name="split_type" id="split_type" class="form-control" required
                                onchange="toggleSplitValueInput()">
                                <option value="percentage">Percentage</option>
                                <option value="flat">Flat</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="business_mobile">Business Mobile</label>
                            <input type="text" name="business_mobile" id="business_mobile" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Create Subaccount</button>
                    </form>

                    <script>
                        function toggleSplitValueInput() {
                            var splitType = document.getElementById('split_type').value;
                            var splitValueInput = document.getElementById('split_value');

                            if (splitType === 'flat') {
                                splitValueInput.setAttribute('type', 'number');
                                splitValueInput.setAttribute('step', '1'); // Disable decimals for flat value
                            } else {
                                splitValueInput.setAttribute('type', 'number');
                                splitValueInput.setAttribute('step', '0.01'); // Enable decimals for percentage
                            }
                        }

                        toggleSplitValueInput();
                    </script>
                </div>
            </div>
        </div>

        <!-- Right Column: Table displaying subaccounts -->
        <div class="col-lg-6">
            <h1 class="h3 mb-3"><strong>Subaccounts List</strong></h1>
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"> Subaccounts</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Business Name</th>
                                <th>Account Bank</th>
                                <th>Account Number</th>
                                <th>Country</th>
                                <th>Split Value</th>
                                <th>Split Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($subaccounts) && count($subaccounts) > 0)
                                @foreach ($subaccounts as $subaccount)
                                    <tr>
                                        <td>{{ $subaccount->business_name }}</td>
                                        <td>{{ $subaccount->account_bank }}</td>
                                        <td>{{ $subaccount->account_number }}</td>
                                        <td>{{ $subaccount->country }}</td>
                                        <td>{{ $subaccount->split_value }}</td>
                                        <td>{{ $subaccount->split_type }}</td>
                                        <td>
                                            <a href="{{ route('subaccounts.edit', $subaccount->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form action="#"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <p>No subaccounts found.</p>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
