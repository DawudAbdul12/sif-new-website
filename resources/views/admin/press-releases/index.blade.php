@extends('admin.layouts.app')

@section('title', 'Press Releases')
@section('page_title', 'Press Releases')
@section('page_subtitle', 'Publish official releases with downloadable files.')

@section('content')
  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 260px" placeholder="Search press releases">
        <select name="status" class="admin-select" style="width: 180px">
          <option value="">All statuses</option>
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit">Filter</button>
      </form>
      <a href="{{ route('admin.press-releases.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Release</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>File</th>
          <th>Status</th>
          <th>Published</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($pressReleases as $pressRelease)
          <tr>
            <td>
              <div class="fw-bold">{{ $pressRelease->title }}</div>
              <div class="small text-muted">{{ Str::limit($pressRelease->brief_description, 90) }}</div>
            </td>
            <td>{{ $pressRelease->file_name ?: 'No file' }}</td>
            <td><span class="admin-badge {{ $pressRelease->status }}">{{ ucfirst($pressRelease->status) }}</span></td>
            <td>{{ $pressRelease->published_at?->format('M j, Y') ?? 'Not published' }}</td>
            <td class="text-end"><a href="{{ route('admin.press-releases.edit', $pressRelease) }}" class="admin-btn-secondary">Edit</a></td>
          </tr>
        @empty
          @include('admin.partials.empty-table', [
            'colspan' => 5,
            'icon' => 'bi-megaphone',
            'title' => request()->query() ? 'No releases match your filters' : 'No press releases yet',
            'message' => request()->query() ? 'Try another title, file name, or publishing status.' : 'Publish official press releases with supporting files and public dates.',
            'actionLabel' => 'New Release',
            'actionUrl' => route('admin.press-releases.create'),
            'resetUrl' => route('admin.press-releases.index'),
          ])
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">{{ $pressReleases->links() }}</div>
  </section>
@endsection
