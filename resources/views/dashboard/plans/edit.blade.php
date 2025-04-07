@extends('layouts.dash')

@section('content')
    <h1 class="h3 mb-3"><strong>Edit Plan</strong></h1>

    <div class="col-lg-6 col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Edit Plan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('plans.update', $plan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Plan Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $plan->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="membership_category_id" class="form-label">Membership Category</label>
                        <select name="membership_category_id" class="form-control" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $plan->membership_category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" class="form-control" value="{{ $plan->price }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="billing_cycle" class="form-label">Billing Cycle</label>
                        <select name="billing_cycle" class="form-control" required>
                            <option value="monthly" {{ $plan->billing_cycle == 'monthly' ? 'selected' : '' }}>Monthly
                            </option>
                            <option value="quarterly" {{ $plan->billing_cycle == 'quarterly' ? 'selected' : '' }}>Quarterly
                            </option>
                            <option value="annually" {{ $plan->billing_cycle == 'annually' ? 'selected' : '' }}>Annually
                            </option>
                            <option value="lifetime" {{ $plan->billing_cycle == 'lifetime' ? 'selected' : '' }}>Lifetime
                            </option>
                            <option value="No_Annual_Fee" {{ $plan->billing_cycle == 'No_Annual_Fee' ? 'selected' : '' }}>No Annual Subscription Fees
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ $plan->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $plan->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Plan</button>
                    <a href="{{ route('plans.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
