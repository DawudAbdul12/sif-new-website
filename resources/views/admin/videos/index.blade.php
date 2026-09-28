@extends('admin.layouts.app')

@section('title', 'Videos')
@section('page_title', 'Videos')
@section('page_subtitle', 'Manage videos shown on the public media page.')

@push('styles')
  <style>
    .video-toolbar {
      align-items: center;
      border-bottom: 1px solid var(--gb-line);
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      justify-content: space-between;
      margin: -4px -4px 18px;
      padding: 4px 4px 18px;
    }

    .video-filter {
      display: grid;
      gap: 10px;
      grid-template-columns: minmax(220px, 320px) 180px auto auto;
    }

    .video-list {
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      overflow: hidden;
    }

    .video-row {
      align-items: center;
      background: #fff;
      border-bottom: 1px solid #edf1ee;
      display: grid;
      gap: 18px;
      grid-template-columns: minmax(0, 1fr) 120px 90px 130px 92px;
      padding: 14px;
    }

    .video-row:last-child {
      border-bottom: 0;
    }

    .video-row:hover {
      background: #fbfcf8;
    }

    .video-title-cell {
      align-items: center;
      display: grid;
      gap: 14px;
      grid-template-columns: 138px minmax(0, 1fr);
      min-width: 0;
    }

    .video-thumb-admin {
      aspect-ratio: 16 / 9;
      background: #102217;
      border-radius: 8px;
      overflow: hidden;
      position: relative;
    }

    .video-thumb-admin img {
      height: 100%;
      object-fit: cover;
      width: 100%;
    }

    .video-thumb-admin::after {
      align-items: center;
      background: rgba(23, 71, 45, 0.86);
      border: 1px solid rgba(216, 180, 73, 0.45);
      border-radius: 999px;
      color: #fff;
      content: "\F4F4";
      display: flex;
      font-family: bootstrap-icons;
      font-size: 1rem;
      height: 34px;
      justify-content: center;
      left: 50%;
      position: absolute;
      top: 50%;
      transform: translate(-50%, -50%);
      width: 34px;
    }

    .video-title-copy {
      min-width: 0;
    }

    .video-title-copy a {
      color: var(--gb-green);
      display: inline-block;
      font-weight: 800;
      margin-bottom: 4px;
      text-decoration: none;
    }

    .video-title-copy a:hover {
      color: var(--gb-green-dark);
      text-decoration: underline;
    }

    .video-url {
      color: var(--gb-muted);
      font-size: 0.78rem;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .video-empty {
      align-items: center;
      display: grid;
      gap: 12px;
      justify-items: center;
      padding: 54px 18px;
      text-align: center;
    }

    .video-empty i {
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
      .video-filter,
      .video-row,
      .video-title-cell {
        grid-template-columns: 1fr;
      }

      .video-row {
        align-items: stretch;
      }
    }
  </style>
@endpush

@section('content')
  <section class="admin-panel">
    <div class="video-toolbar">
      <form method="GET" class="video-filter">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" placeholder="Search videos">
        <select name="status" class="admin-select">
          <option value="">All statuses</option>
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
        @if(request()->filled('search') || request()->filled('status'))
          <a href="{{ route('admin.videos.index') }}" class="admin-btn-secondary">Reset</a>
        @endif
      </form>
      <a href="{{ route('admin.videos.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Video</a>
    </div>

    <div class="video-list">
      @forelse($videos as $video)
        <div class="video-row">
          <div class="video-title-cell">
            <div class="video-thumb-admin">
              <img src="{{ $video->thumbnailUrl() }}" alt="{{ $video->title }}">
            </div>
            <div class="video-title-copy">
              <a href="{{ route('admin.videos.edit', $video) }}">{{ $video->title }}</a>
              <div class="small text-muted">{{ Str::limit($video->description, 110) ?: 'No description added.' }}</div>
              <div class="video-url">{{ $video->video_url }}</div>
            </div>
          </div>
          <div><span class="admin-badge {{ $video->status }}">{{ ucfirst($video->status) }}</span></div>
          <div>
            <div class="small text-muted">Sort</div>
            <strong>{{ $video->sort_order }}</strong>
          </div>
          <div>
            <div class="small text-muted">Published</div>
            <strong>{{ $video->published_at?->format('M j, Y') ?? 'Not set' }}</strong>
          </div>
          <div class="text-end"><a href="{{ route('admin.videos.edit', $video) }}" class="admin-btn-secondary">Edit</a></div>
        </div>
      @empty
        <div class="video-empty">
          <i class="bi bi-play-btn"></i>
          <div>
            <h2 class="h5 fw-bold mb-1 text-success">No videos yet</h2>
            <p class="text-muted mb-0">Add the first video to populate the public media page.</p>
          </div>
          <a href="{{ route('admin.videos.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Video</a>
        </div>
      @endforelse
    </div>

    <div class="mt-3">{{ $videos->links() }}</div>
  </section>
@endsection
