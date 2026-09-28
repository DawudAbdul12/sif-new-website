@extends('admin.layouts.app')

@section('title', 'Users')
@section('page_title', 'Users')
@section('page_subtitle', 'Manage CMS access, roles, and permissions.')

@section('content')
  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 260px" placeholder="Search users">
        <button class="admin-btn-secondary" type="submit">Search</button>
      </form>
      <a href="{{ route('admin.users.create') }}" class="admin-btn"><i class="bi bi-person-plus"></i> New User</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Created</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
          <tr>
            <td class="fw-bold">{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
              @if($user->roles->isNotEmpty())
                <div class="d-flex flex-wrap gap-1">
                  @foreach($user->roles as $role)
                    <span class="admin-badge">{{ $role->name }}</span>
                  @endforeach
                </div>
              @else
                <span class="admin-badge">{{ $user->is_admin ? 'Admin' : 'User' }}</span>
              @endif
            </td>
            <td>{{ $user->created_at->format('M j, Y') }}</td>
            <td class="text-end"><a href="{{ route('admin.users.edit', $user) }}" class="admin-btn-secondary">Edit</a></td>
          </tr>
        @empty
          @include('admin.partials.empty-table', [
            'colspan' => 5,
            'icon' => 'bi-people',
            'title' => request()->query() ? 'No users match your search' : 'No users found',
            'message' => request()->query() ? 'Try a different name or email address.' : 'Invite CMS users and assign roles so the right people can manage the website.',
            'actionLabel' => 'New User',
            'actionUrl' => route('admin.users.create'),
            'resetUrl' => route('admin.users.index'),
          ])
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">{{ $users->links() }}</div>
  </section>
@endsection
