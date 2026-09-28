@extends('admin.layouts.app')

@section('title', 'Roles')
@section('page_title', 'Roles')
@section('page_subtitle', 'Manage CMS roles and permission groups.')

@section('content')
  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 260px" placeholder="Search roles">
        <button class="admin-btn-secondary" type="submit">Search</button>
      </form>
      <a href="{{ route('admin.roles.create') }}" class="admin-btn"><i class="bi bi-shield-plus"></i> New Role</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Role</th>
          <th>Permissions</th>
          <th>Users</th>
          <th>Created</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($roles as $role)
          <tr>
            <td>
              <div class="fw-bold">{{ $role->name }}</div>
              <div class="small text-muted">{{ $role->slug }}</div>
            </td>
            <td>
              <span class="admin-badge">{{ $role->permissions->count() }} permissions</span>
            </td>
            <td>{{ number_format($role->users_count) }}</td>
            <td>{{ $role->created_at->format('M j, Y') }}</td>
            <td class="text-end"><a href="{{ route('admin.roles.edit', $role) }}" class="admin-btn-secondary">Edit</a></td>
          </tr>
        @empty
          @include('admin.partials.empty-table', [
            'colspan' => 5,
            'icon' => 'bi-shield-lock',
            'title' => request()->query() ? 'No roles match your search' : 'No roles found',
            'message' => request()->query() ? 'Try a different role name or slug.' : 'Create permission groups for editors, reviewers, publishers, and administrators.',
            'actionLabel' => 'New Role',
            'actionUrl' => route('admin.roles.create'),
            'resetUrl' => route('admin.roles.index'),
          ])
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">{{ $roles->links() }}</div>
  </section>
@endsection
