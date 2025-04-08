<form method="POST" action="{{ isset($role) ? route('roles.update', $role) : route('roles.store') }}">
    @csrf
    @if(isset($role)) @method('PUT') @endif

    <div class="mb-4">
        <label class="block font-bold mb-1">Role Name</label>
        <input type="text" name="name" value="{{ old('name', $role->name ?? '') }}"
               class="w-full px-4 py-2 border rounded" required>
    </div>

    <div class="mb-4">
        <label class="block font-bold mb-1">Permissions</label>
        <div class="grid grid-cols-2 gap-2">
            @foreach ($permissions as $permission)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="permissions[]"
                           value="{{ $permission->name }}"
                           {{ (isset($rolePermissions) && in_array($permission->name, $rolePermissions)) ? 'checked' : '' }}>
                    <span>{{ $permission->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <button type="submit"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        {{ isset($role) ? 'Update Role' : 'Create Role' }}
    </button>
</form>
