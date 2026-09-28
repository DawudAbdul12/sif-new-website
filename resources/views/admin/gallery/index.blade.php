@extends('admin.layouts.app')

@section('title', 'Gallery')
@section('page_title', 'Gallery')
@section('page_subtitle', 'Manage photo albums shown on the public gallery page.')

@push('styles')
  <style>
    .gallery-toolbar {
      align-items: center;
      border-bottom: 1px solid var(--gb-line);
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      justify-content: space-between;
      margin: -4px -4px 18px;
      padding: 4px 4px 18px;
    }

    .gallery-filter {
      display: grid;
      gap: 10px;
      grid-template-columns: minmax(220px, 320px) 180px auto auto;
    }

    .gallery-admin-grid {
      display: grid;
      gap: 18px;
      grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    }

    .gallery-summary {
      align-items: center;
      background: #fbfcf8;
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: space-between;
      margin-bottom: 18px;
      padding: 12px 14px;
    }

    .gallery-summary strong {
      color: var(--gb-green);
    }

    .gallery-summary span {
      color: var(--gb-muted);
      font-size: .84rem;
      font-weight: 700;
    }

    .gallery-admin-card {
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      color: var(--gb-ink);
      display: block;
      overflow: hidden;
      text-decoration: none;
      transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }

    .gallery-admin-card:hover {
      border-color: rgba(216, 180, 73, .8);
      box-shadow: 0 16px 32px rgba(24, 72, 45, .1);
      transform: translateY(-2px);
    }

    .gallery-admin-cover {
      aspect-ratio: 16 / 10;
      background: #102217;
      overflow: hidden;
      position: relative;
    }

    .gallery-admin-cover img {
      height: 100%;
      object-fit: cover;
      width: 100%;
    }

    .gallery-admin-count {
      background: rgba(16, 55, 32, .88);
      border-radius: 999px;
      bottom: 10px;
      color: #fff;
      font-size: .78rem;
      font-weight: 800;
      padding: 6px 10px;
      position: absolute;
      right: 10px;
    }

    .gallery-admin-body {
      padding: 14px;
    }

    .gallery-admin-title {
      color: var(--gb-green);
      font-weight: 800;
      line-height: 1.35;
      margin-bottom: 6px;
    }

    .gallery-admin-meta {
      align-items: center;
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      justify-content: space-between;
      margin-top: 14px;
    }

    .gallery-empty {
      align-items: center;
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      display: grid;
      gap: 12px;
      justify-items: center;
      padding: 54px 18px;
      text-align: center;
    }

    .gallery-empty i {
      align-items: center;
      background: #edf5ef;
      border-radius: 999px;
      color: var(--gb-green);
      display: flex;
      font-size: 1.8rem;
      height: 64px;
      justify-content: center;
      width: 64px;
    }

    @media (max-width: 980px) {
      .gallery-filter {
        grid-template-columns: 1fr;
      }
    }
  </style>
@endpush

@section('content')
  <section class="admin-panel">
    <div class="gallery-toolbar">
      <form method="GET" class="gallery-filter">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" placeholder="Search albums">
        <select name="status" class="admin-select">
          <option value="">All statuses</option>
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
        @if(request()->filled('search') || request()->filled('status'))
          <a href="{{ route('admin.gallery.index') }}" class="admin-btn-secondary">Reset</a>
        @endif
      </form>
      <a href="{{ route('admin.gallery.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Album</a>
    </div>

    @if($albums->total())
      <div class="gallery-summary">
        <span><strong>{{ $albums->total() }}</strong> {{ Str::plural('album', $albums->total()) }}</span>
        <span>Showing {{ $albums->firstItem() }}-{{ $albums->lastItem() }}</span>
      </div>
    @endif

    <div class="gallery-admin-grid">
      @forelse($albums as $album)
        <a href="{{ route('admin.gallery.edit', $album) }}" class="gallery-admin-card">
          <div class="gallery-admin-cover">
            <img src="{{ $album->coverUrl() }}" alt="{{ $album->title }}">
            <span class="gallery-admin-count">{{ $album->images_count }} {{ Str::plural('photo', $album->images_count) }}</span>
          </div>
          <div class="gallery-admin-body">
            <div class="gallery-admin-title">{{ $album->title }}</div>
            <div class="small text-muted">{{ Str::limit($album->description, 90) ?: 'No description added.' }}</div>
            <div class="gallery-admin-meta">
              <span class="admin-badge {{ $album->status }}">{{ ucfirst($album->status) }}</span>
              <span class="small text-muted">{{ $album->published_at?->format('M j, Y') ?? 'Not published' }}</span>
            </div>
          </div>
        </a>
      @empty
        <div class="gallery-empty">
          <i class="bi bi-images"></i>
          <div>
            <h2 class="h5 fw-bold mb-1 text-success">No albums yet</h2>
            <p class="text-muted mb-0">Add a photo album to populate the public gallery.</p>
          </div>
          <a href="{{ route('admin.gallery.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Album</a>
        </div>
      @endforelse
    </div>

    <div class="mt-3">{{ $albums->links() }}</div>
  </section>
@endsection
