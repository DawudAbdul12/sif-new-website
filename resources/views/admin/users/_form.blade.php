@csrf

<div class="admin-panel">
  <div class="row g-3">
    <div class="col-md-6">
      <label class="admin-label" for="name">Name</label>
      <input id="name" name="name" value="{{ old('name', $user->name) }}" class="admin-control" required>
      @error('name') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
      <label class="admin-label" for="email">Email</label>
      <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="admin-control" required>
      @error('email') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
      <label class="admin-label" for="password">Password</label>
      <input id="password" type="password" name="password" class="admin-control" @if(! $user->exists) required @endif>
      <p class="small text-muted mt-2 mb-0">{{ $user->exists ? 'Leave blank to keep the current password.' : 'Minimum 10 characters.' }}</p>
      @error('password') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
      <label class="admin-label">Access</label>
      <div class="form-check mt-2">
        <input id="is_admin" type="checkbox" name="is_admin" value="1" class="form-check-input" @checked(old('is_admin', $user->is_admin))>
        <label for="is_admin" class="form-check-label fw-bold text-success">Grant admin access</label>
      </div>
      <p class="small text-muted mt-2 mb-0">Only users with admin access can sign in to the CMS.</p>
    </div>

    <div class="col-12">
      <label class="admin-label">Roles</label>
      @php
        $selectedRoles = collect(old('roles', $user->roles->pluck('id')->all()))->map(fn ($id) => (int) $id);
      @endphp
      <div class="row g-2">
        @forelse($roles as $role)
          <div class="col-md-6 col-xl-4">
            <label class="border rounded-2 p-3 h-100 d-flex gap-2" for="role_{{ $role->id }}">
              <input id="role_{{ $role->id }}" type="checkbox" name="roles[]" value="{{ $role->id }}" class="form-check-input mt-1" @checked($selectedRoles->contains($role->id))>
              <span>
                <span class="d-block fw-bold text-success">{{ $role->name }}</span>
                <span class="d-block small text-muted">{{ $role->description ?: $role->slug }}</span>
              </span>
            </label>
          </div>
        @empty
          <div class="col-12">
            <p class="text-muted mb-0">No roles have been created yet.</p>
          </div>
        @endforelse
      </div>
      @error('roles') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
    </div>
  </div>

  <button class="admin-btn mt-3" type="submit"><i class="bi bi-check2"></i> Save User</button>
</div>
