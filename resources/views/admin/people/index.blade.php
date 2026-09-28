@extends('admin.layouts.app')

@section('title', $groupLabel)
@section('page_title', $groupLabel)
@section('page_subtitle', 'Manage profile cards, photos, roles, ordering, and publishing status.')

@section('content')
  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 260px" placeholder="Search profiles">
        <select name="status" class="admin-select" style="width: 180px">
          <option value="">All statuses</option>
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit">Filter</button>
      </form>

      <a href="{{ route('admin.people.create', $group) }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Profile</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Profile</th>
          <th>Position</th>
          <th>Status</th>
          <th>Order</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($people as $person)
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <img src="{{ $person->photoUrl() }}" alt="{{ $person->name }}" style="width:56px;height:56px;object-fit:cover;border-radius:6px;border:1px solid #edf1ee;">
                <div>
                  <div class="fw-bold">{{ $person->name }}</div>
                  <div class="small text-muted">{{ Str::limit($person->brief_profile, 80) }}</div>
                </div>
              </div>
            </td>
            <td>
              <div>{{ $person->position ?: '-' }}</div>
              @if($person->department)
                <div class="small text-muted">{{ $person->department }}</div>
              @endif
            </td>
            <td><span class="admin-badge {{ $person->status }}">{{ ucfirst($person->status) }}</span></td>
            <td>{{ $person->sort_order }}</td>
            <td class="text-end">
              <a href="{{ route('admin.people.edit', [$group, $person]) }}" class="admin-btn-secondary">Edit</a>
            </td>
          </tr>
        @empty
          @include('admin.partials.empty-table', [
            'colspan' => 5,
            'icon' => 'bi-person-badge',
            'title' => request()->query() ? 'No profiles match your filters' : 'No profiles yet',
            'message' => request()->query() ? 'Try another name, position, or publishing status.' : 'Add leadership profiles with photos, roles, biographies, and display ordering.',
            'actionLabel' => 'New Profile',
            'actionUrl' => route('admin.people.create', $group),
            'resetUrl' => route('admin.people.index', $group),
          ])
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">{{ $people->links() }}</div>
  </section>
@endsection
