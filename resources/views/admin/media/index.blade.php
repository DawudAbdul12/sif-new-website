@extends('admin.layouts.app')

@section('title', 'Media')
@section('page_title', 'Media Library')
@section('page_subtitle', 'Upload and manage CMS assets.')

@push('styles')
  <style>
    .admin-pagination-wrap {
      align-items: center;
      border-top: 1px solid var(--gb-line);
      display: flex;
      gap: 16px;
      justify-content: space-between;
      margin-top: 24px;
      padding-top: 18px;
    }

    .admin-pagination-wrap .pagination {
      gap: 6px;
      margin-bottom: 0;
    }

    .admin-pagination-wrap .page-link {
      border: 1px solid #dfe6e1;
      border-radius: 6px;
      color: var(--gb-green);
      font-weight: 800;
      min-width: 38px;
      text-align: center;
    }

    .admin-pagination-wrap .page-item.active .page-link {
      background: var(--gb-green);
      border-color: var(--gb-green);
      color: #fff;
    }

    .admin-pagination-wrap .page-item.disabled .page-link {
      color: #98a29c;
    }

    .media-count {
      color: var(--gb-muted);
      font-size: 0.86rem;
      font-weight: 700;
    }

    @media (max-width: 760px) {
      .admin-pagination-wrap {
        align-items: flex-start;
        flex-direction: column;
      }
    }
  </style>
@endpush

@section('content')
  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 260px" placeholder="Search media">
        <select name="per_page" class="admin-select" style="width: 150px" aria-label="Items per page">
          @foreach([12, 18, 24, 36, 48] as $size)
            <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }} / page</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit"><i class="bi bi-search"></i> Search</button>
      </form>
      <a href="{{ route('admin.media.create') }}" class="admin-btn"><i class="bi bi-upload"></i> Upload Media</a>
    </div>

    <div class="media-count mb-3">
      @if($assets->total())
        Showing {{ $assets->firstItem() }}-{{ $assets->lastItem() }} of {{ $assets->total() }} media assets
      @else
        No media assets found
      @endif
    </div>

    <div class="row g-3">
      @forelse($assets as $asset)
        <div class="col-md-4 col-xl-3">
          <div class="admin-card">
            @if(str_starts_with((string) $asset->mime_type, 'image/'))
              <img src="{{ $asset->url() }}" alt="{{ $asset->alt_text }}" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:6px;border:1px solid #edf1ee;">
            @else
              <div class="d-grid place-items-center bg-light text-center p-4 rounded border" style="min-height:140px">
                <i class="bi bi-file-earmark fs-1 text-success"></i>
              </div>
            @endif
            <h2 class="h6 fw-bold mt-3 mb-1">{{ $asset->name }}</h2>
            <p class="small text-muted mb-3">{{ $asset->mime_type ?: 'Unknown type' }}</p>
            <a href="{{ route('admin.media.edit', $asset) }}" class="admin-btn-secondary w-100">Edit</a>
          </div>
        </div>
      @empty
        <div class="col-12 text-muted">No media uploaded yet.</div>
      @endforelse
    </div>

    @if($assets->hasPages())
      <div class="admin-pagination-wrap">
        <div class="media-count">Page {{ $assets->currentPage() }} of {{ $assets->lastPage() }}</div>
        {{ $assets->onEachSide(1)->links() }}
      </div>
    @endif
  </section>
@endsection
