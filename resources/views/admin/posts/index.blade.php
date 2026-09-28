@extends('admin.layouts.app')

@section('title', 'Posts')
@section('page_title', 'Posts')
@section('page_subtitle', 'Manage news and article content.')

@section('content')
  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 240px" placeholder="Search posts">
        <select name="type" class="admin-select" style="width: 160px">
          <option value="">All types</option>
          @foreach(['news', 'article'] as $type)
            <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
          @endforeach
        </select>
        <select name="category_id" class="admin-select" style="width: 210px">
          <option value="">All categories</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
          @endforeach
        </select>
        <select name="status" class="admin-select" style="width: 170px">
          <option value="">All statuses</option>
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit">Filter</button>
      </form>
      <a href="{{ route('admin.posts.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Post</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Type</th>
          <th>Category</th>
          <th>Status</th>
          <th>Published</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($posts as $post)
          <tr>
            <td class="fw-bold">{{ $post->title }}</td>
            <td>{{ ucfirst($post->type) }}</td>
            <td>{{ $post->categoryRelation?->name ?? 'Uncategorized' }}</td>
            <td><span class="admin-badge {{ $post->status }}">{{ ucfirst($post->status) }}</span></td>
            <td>{{ $post->published_at?->format('M j, Y') ?? 'Not published' }}</td>
            <td class="text-end"><a href="{{ route('admin.posts.edit', $post) }}" class="admin-btn-secondary">Edit</a></td>
          </tr>
        @empty
          @include('admin.partials.empty-table', [
            'colspan' => 6,
            'icon' => 'bi-newspaper',
            'title' => request()->query() ? 'No posts match your filters' : 'No posts yet',
            'message' => request()->query() ? 'Try another keyword, content type, category, or publishing status.' : 'Create your first news item or article and it will appear in this publishing table.',
            'actionLabel' => 'New Post',
            'actionUrl' => route('admin.posts.create'),
            'resetUrl' => route('admin.posts.index'),
          ])
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">@include('partials.compact-pagination', ['paginator' => $posts])</div>
  </section>
@endsection
