@csrf

@push('styles')
  <style>
    .permission-matrix {
      display: grid;
      gap: 18px;
    }

    .permission-group {
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      overflow: hidden;
    }

    .permission-group-head {
      align-items: center;
      background: #f8faf9;
      border-bottom: 1px solid var(--gb-line);
      display: flex;
      justify-content: space-between;
      gap: 12px;
      padding: 12px 14px;
    }

    .permission-group-title {
      color: var(--gb-green);
      font-size: .86rem;
      font-weight: 900;
      text-transform: uppercase;
    }

    .permission-group-count {
      color: var(--gb-muted);
      font-size: .74rem;
      font-weight: 850;
      text-transform: uppercase;
    }

    .permission-row,
    .permission-header {
      align-items: stretch;
      border-top: 1px solid var(--gb-line);
      display: grid;
      grid-template-columns: minmax(170px, 1.2fr) repeat(4, minmax(88px, .55fr)) minmax(180px, 1fr);
    }

    .permission-header {
      background: #fff;
      border-top: 0;
      color: var(--gb-muted);
      font-size: .7rem;
      font-weight: 900;
      text-transform: uppercase;
    }

    .permission-cell {
      align-items: center;
      border-left: 1px solid var(--gb-line);
      display: flex;
      min-width: 0;
      padding: 11px 12px;
    }

    .permission-cell:first-child {
      border-left: 0;
    }

    .permission-resource {
      color: var(--gb-green);
      display: block;
      font-weight: 900;
      overflow-wrap: anywhere;
    }

    .permission-code {
      color: var(--gb-muted);
      display: block;
      font-size: .72rem;
      font-weight: 750;
      margin-top: 2px;
    }

    .permission-check {
      align-items: center;
      cursor: pointer;
      display: inline-flex;
      gap: 8px;
      margin: 0;
      min-height: 24px;
    }

    .permission-check-label {
      color: var(--gb-ink);
      font-size: .8rem;
      font-weight: 800;
    }

    .permission-missing {
      color: #b8c2bc;
      font-weight: 900;
      line-height: 1;
    }

    .permission-extra-list {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .permission-extra {
      align-items: center;
      background: #f8faf9;
      border: 1px solid var(--gb-line);
      border-radius: 999px;
      display: inline-flex;
      gap: 7px;
      padding: 6px 9px;
    }

    @media (max-width: 991.98px) {
      .permission-header {
        display: none;
      }

      .permission-row {
        grid-template-columns: 1fr;
      }

      .permission-cell {
        border-left: 0;
        border-top: 1px solid var(--gb-line);
        justify-content: space-between;
      }

      .permission-cell:first-child {
        border-top: 0;
      }

      .permission-cell[data-label]::before {
        color: var(--gb-muted);
        content: attr(data-label);
        font-size: .72rem;
        font-weight: 900;
        text-transform: uppercase;
      }
    }
  </style>
@endpush

<div class="admin-panel">
  <div class="row g-3">
    <div class="col-md-6">
      <label class="admin-label" for="name">Role Name</label>
      <input id="name" name="name" value="{{ old('name', $role->name) }}" class="admin-control" required>
      @error('name') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
      <label class="admin-label" for="slug">Slug</label>
      <input id="slug" name="slug" value="{{ old('slug', $role->slug) }}" class="admin-control" @readonly($role->is_system)>
      @error('slug') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
      <label class="admin-label" for="description">Description</label>
      <textarea id="description" name="description" class="admin-control" rows="3">{{ old('description', $role->description) }}</textarea>
      @error('description') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
    </div>
  </div>

  @php
    $selectedPermissions = collect(old('permissions', $role->permissions->pluck('id')->all()))->map(fn ($id) => (int) $id);
  @endphp

  <div class="mt-4">
    <div class="admin-label mb-2">Permissions</div>
    <div class="permission-matrix">
      @foreach($permissions as $group => $groupPermissions)
        @php
          $actions = ['view' => 'View', 'create' => 'Create', 'update' => 'Edit', 'delete' => 'Delete'];
          $resources = $groupPermissions
            ->groupBy(fn ($permission) => str($permission->name)->before('.')->toString())
            ->sortKeys();
        @endphp

        <div class="permission-group">
          <div class="permission-group-head">
            <div class="permission-group-title">{{ $group }}</div>
            <div class="permission-group-count">{{ $groupPermissions->count() }} permissions</div>
          </div>

          <div class="permission-header">
            <div class="permission-cell">Area</div>
            @foreach($actions as $actionLabel)
              <div class="permission-cell">{{ $actionLabel }}</div>
            @endforeach
            <div class="permission-cell">Actions</div>
          </div>

          @foreach($resources as $resource => $resourcePermissions)
            @php
              $byAction = $resourcePermissions->keyBy(fn ($permission) => str($permission->name)->after('.')->toString());
              $extras = $byAction->except(array_keys($actions));
            @endphp

            <div class="permission-row">
              <div class="permission-cell">
                <span>
                  <span class="permission-resource">{{ str($resource)->headline() }}</span>
                  <span class="permission-code">{{ $resource }}.*</span>
                </span>
              </div>

              @foreach($actions as $action => $actionLabel)
                @php($permission = $byAction->get($action))
                <div class="permission-cell" data-label="{{ $actionLabel }}">
                  @if($permission)
                    <label class="permission-check" for="permission_{{ $permission->id }}">
                      <input id="permission_{{ $permission->id }}" type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input" @checked($selectedPermissions->contains($permission->id))>
                      <span class="permission-check-label">{{ $actionLabel }}</span>
                    </label>
                  @else
                    <span class="permission-missing">-</span>
                  @endif
                </div>
              @endforeach

              <div class="permission-cell" data-label="Actions">
                @if($extras->isNotEmpty())
                  <div class="permission-extra-list">
                    @foreach($extras as $permission)
                      <label class="permission-extra" for="permission_{{ $permission->id }}">
                        <input id="permission_{{ $permission->id }}" type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input m-0" @checked($selectedPermissions->contains($permission->id))>
                        <span class="permission-check-label">{{ str($permission->name)->after('.')->headline() }}</span>
                      </label>
                    @endforeach
                  </div>
                @else
                  <span class="permission-missing">-</span>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      @endforeach
    </div>
    @error('permissions') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
  </div>

  <button class="admin-btn mt-4" type="submit"><i class="bi bi-check2"></i> Save Role</button>
</div>
