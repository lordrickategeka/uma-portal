<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $branch->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Location</label>
    <input type="text" name="location" class="form-control" value="{{ old('location', $branch->location ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Address</label>
    <input type="text" name="address" class="form-control" value="{{ old('address', $branch->address ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $branch->email ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Phone</label>
    <input type="text" name="phone_number" class="form-control" value="{{ old('phone', $branch->phone_number ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Manager</label>
    <input type="text" name="manager_name" class="form-control" value="{{ old('manager', $branch->manager_name ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-control">
        <option value="active" {{ old('status', $branch->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ old('status', $branch->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
</div>
<button type="submit" class="btn btn-success">{{ $button }}</button>