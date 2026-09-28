@extends('admin.layouts.app')

@section('title', 'Categories')
@section('page_title', 'Post Categories')
@section('page_subtitle', 'Organize news and articles with premium publishing taxonomy.')

@section('content')
  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 240px" placeholder="Search categories">
        <select name="type" class="admin-select" style="width: 160px">
          <option value="">All types</option>
          @foreach(['all' => 'All posts', 'news' => 'News', 'article' => 'Article'] as $type => $label)
            <option value="{{ $type }}" @selected(request('type') === $type)>{{ $label }}</option>
          @endforeach
        </select>
        <select name="status" class="admin-select" style="width: 160px">
          <option value="">All statuses</option>
          @foreach(['active', 'inactive'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit">Filter</button>
      </form>
      <a href="{{ route('admin.categories.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Category</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Scope</th>
          <th>Status</th>
          <th>Posts</th>
          <th>Order</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $category)
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2 fw-bold">
                <span style="width: 14px; height: 14px; border-radius: 50%; background: {{ $category->color }}; display: inline-block"></span>
                {{ $category->name }}
              </div>
              <div class="small text-muted">/{{ $category->slug }}</div>
            </td>
            <td>{{ $category->typeLabel() }}</td>
            <td><span class="admin-badge {{ $category->status === 'inactive' ? 'archived' : '' }}">{{ ucfirst($category->status) }}</span></td>
            <td>{{ $category->posts_count }}</td>
            <td>{{ $category->sort_order }}</td>
            <td class="text-end"><a href="{{ route('admin.categories.edit', $category) }}" class="admin-btn-secondary">Edit</a></td>
          </tr>
        @empty
          @include('admin.partials.empty-table', [
            'colspan' => 6,
            'icon' => 'bi-tags',
            'title' => request()->query() ? 'No categories match your filters' : 'No post categories yet',
            'message' => request()->query() ? 'Adjust the category name, scope, or status filters.' : 'Create categories to organize news and article content across the CMS.',
            'actionLabel' => 'New Category',
            'actionUrl' => route('admin.categories.create'),
            'resetUrl' => route('admin.categories.index'),
          ])
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">{{ $categories->links() }}</div>
  </section>
@endsection
