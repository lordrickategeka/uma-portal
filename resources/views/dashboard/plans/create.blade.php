@extends('layouts.dash')

@section('content')
<div class="col-lg-6">
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><strong>Create Plan</strong></h5>
        </div>
        <div class="card-body">
            <form action="{{ route('plans.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Plan Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="membership_category_id" class="form-label">Membership Category</label>
                    <select name="membership_category_id" class="form-control" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" name="price" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="billing_cycle" class="form-label">Billing Cycle</label>
                    <select name="billing_cycle" class="form-control" required>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="annually">Annually</option>
                        <option value="lifetime">Lifetime</option>
                        <option value="No_Annual_Fee">No Annual Subscription Fees</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Save Plan</button>
            </form>
        </div>
    </div>
</div>
@endsection
