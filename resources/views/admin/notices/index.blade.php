@extends('admin.layouts.app')

@section('title', 'Notices')
@section('page_title', 'Notices')
@section('page_subtitle', 'Publish notices with downloadable documents and expiry controls.')

@section('content')
  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 260px" placeholder="Search notices">
        <select name="status" class="admin-select" style="width: 180px">
          <option value="">All statuses</option>
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit">Filter</button>
      </form>
      <a href="{{ route('admin.notices.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Notice</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>File</th>
          <th>Status</th>
          <th>Expires</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($notices as $notice)
          <tr>
            <td>
              <div class="fw-bold">{{ $notice->title }}</div>
              <div class="small text-muted">{{ Str::limit($notice->brief_description, 90) }}</div>
            </td>
            <td>{{ $notice->file_name ?: 'No file' }}</td>
            <td><span class="admin-badge {{ $notice->status }}">{{ ucfirst($notice->status) }}</span></td>
            <td>{{ $notice->expires_at?->format('M j, Y') ?? 'No expiry' }}</td>
            <td class="text-end"><a href="{{ route('admin.notices.edit', $notice) }}" class="admin-btn-secondary">Edit</a></td>
          </tr>
        @empty
          @include('admin.partials.empty-table', [
            'colspan' => 5,
            'icon' => 'bi-pin-angle',
            'title' => request()->query() ? 'No notices match your filters' : 'No notices yet',
            'message' => request()->query() ? 'Try another notice title, file name, or publishing status.' : 'Publish notices with optional files and expiry dates for the public site.',
            'actionLabel' => 'New Notice',
            'actionUrl' => route('admin.notices.create'),
            'resetUrl' => route('admin.notices.index'),
          ])
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">{{ $notices->links() }}</div>
  </section>
@endsection
