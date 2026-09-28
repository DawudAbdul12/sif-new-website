@extends('admin.layouts.app')

@section('title', 'Pages')
@section('page_title', 'Pages')
@section('page_subtitle', 'Create, publish, and organize website pages.')

@section('content')
  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 260px" placeholder="Search pages">
        <select name="status" class="admin-select" style="width: 180px">
          <option value="">All statuses</option>
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit">Filter</button>
      </form>
      <a href="{{ route('admin.pages.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Page</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Slug</th>
          <th>Status</th>
          <th>Updated</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($pages as $page)
          <tr>
            <td class="fw-bold">{{ $page->title }}</td>
            <td>/{{ $page->slug }}</td>
            <td><span class="admin-badge {{ $page->status }}">{{ ucfirst($page->status) }}</span></td>
            <td>{{ $page->updated_at->diffForHumans() }}</td>
            <td class="text-end"><a href="{{ route('admin.pages.edit', $page) }}" class="admin-btn-secondary">Edit</a></td>
          </tr>
        @empty
          @include('admin.partials.empty-table', [
            'colspan' => 5,
            'icon' => 'bi-file-earmark-text',
            'title' => request()->query() ? 'No pages match your filters' : 'No pages yet',
            'message' => request()->query() ? 'Try another page title, slug, or publishing status.' : 'Create reusable website pages for the public site and manage their publishing status here.',
            'actionLabel' => 'New Page',
            'actionUrl' => route('admin.pages.create'),
            'resetUrl' => route('admin.pages.index'),
          ])
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">{{ $pages->links() }}</div>
  </section>
@endsection
