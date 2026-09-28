@extends('admin.layouts.app')

@section('title', 'Graphics')
@section('page_title', 'Graphics')
@section('page_subtitle', 'Manage public graphics and social media artwork.')

@push('styles')
  <style>
    .graphics-overview {
      display: grid;
      gap: 12px;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      margin-bottom: 18px;
    }

    .graphics-overview-card {
      background: #fbfcf8;
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      padding: 14px;
    }

    .graphics-overview-label {
      color: var(--gb-muted);
      font-size: .74rem;
      font-weight: 800;
      text-transform: uppercase;
    }

    .graphics-overview-value {
      color: var(--gb-green);
      font-size: 1.55rem;
      font-weight: 800;
      line-height: 1.1;
      margin-top: 6px;
    }

    .graphics-status-tabs {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 18px;
    }

    .graphics-status-tab {
      align-items: center;
      background: #fff;
      border: 1px solid var(--gb-line);
      border-radius: 999px;
      color: var(--gb-green);
      display: inline-flex;
      font-size: .82rem;
      font-weight: 800;
      gap: 8px;
      padding: 8px 12px;
      text-decoration: none;
    }

    .graphics-status-tab.is-active,
    .graphics-status-tab:hover {
      background: var(--gb-green);
      border-color: var(--gb-green);
      color: #fff;
    }

    .graphics-status-tab span {
      background: rgba(23, 71, 45, .08);
      border-radius: 999px;
      padding: 2px 7px;
    }

    .graphics-status-tab.is-active span,
    .graphics-status-tab:hover span {
      background: rgba(255, 255, 255, .16);
    }

    .graphics-toolbar {
      align-items: center;
      border-bottom: 1px solid var(--gb-line);
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      justify-content: space-between;
      margin: 0 0 18px;
      padding: 4px 4px 18px;
    }

    .graphics-filter {
      display: grid;
      gap: 10px;
      grid-template-columns: minmax(220px, 320px) 180px auto auto;
    }

    .graphics-table-wrap {
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      overflow: hidden;
      width: 100%;
    }

    .graphics-table {
      margin: 0;
      min-width: 900px;
      width: 100%;
    }

    .graphics-table th {
      background: #fbfcf8;
      color: var(--gb-muted);
      font-size: .74rem;
      font-weight: 800;
      padding: 12px 14px;
      text-transform: uppercase;
      vertical-align: middle;
      white-space: nowrap;
    }

    .graphics-table td {
      border-top: 1px solid #edf1ee;
      padding: 14px;
      vertical-align: middle;
    }

    .graphics-table tbody tr:hover {
      background: #fbfcf8;
    }

    .graphics-title-cell {
      align-items: center;
      display: grid;
      gap: 14px;
      grid-template-columns: 76px minmax(0, 1fr);
      min-width: 0;
    }

    .graphics-thumb {
      align-items: center;
      aspect-ratio: 1;
      background: #f4f6f2;
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      display: flex;
      justify-content: center;
      overflow: hidden;
    }

    .graphics-thumb img {
      height: 100%;
      object-fit: contain;
      width: 100%;
    }

    .graphics-title-copy {
      min-width: 0;
    }

    .graphics-title-copy a {
      color: var(--gb-green);
      display: inline-block;
      font-weight: 800;
      line-height: 1.35;
      margin-bottom: 5px;
      text-decoration: none;
    }

    .graphics-title-copy a:hover {
      color: var(--gb-green-dark);
      text-decoration: underline;
    }

    .graphics-description {
      color: var(--gb-muted);
      font-size: .82rem;
      line-height: 1.5;
      max-width: 520px;
    }

    .graphics-source {
      align-items: center;
      color: var(--gb-muted);
      display: inline-flex;
      font-size: .78rem;
      font-weight: 800;
      gap: 8px;
      white-space: nowrap;
    }

    .graphics-order-pill {
      align-items: center;
      background: #edf5ef;
      border-radius: 999px;
      color: var(--gb-green);
      display: inline-flex;
      font-size: .78rem;
      font-weight: 800;
      gap: 6px;
      padding: 6px 10px;
    }

    .graphics-empty {
      align-items: center;
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      display: grid;
      gap: 12px;
      justify-items: center;
      padding: 54px 18px;
      text-align: center;
    }

    .graphics-empty i {
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
      .graphics-overview {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .graphics-filter {
        grid-template-columns: 1fr;
      }

      .graphics-table-wrap {
        overflow-x: auto;
      }
    }

    @media (max-width: 575.98px) {
      .graphics-overview {
        grid-template-columns: 1fr;
      }

      .graphics-toolbar {
        align-items: stretch;
        flex-direction: column;
      }
    }
  </style>
@endpush

@section('content')
  <section class="admin-panel">
    @php
      $statuses = ['draft', 'review', 'published', 'archived'];
      $totalGraphics = $statusCounts->sum();
    @endphp

    <div class="graphics-overview">
      <div class="graphics-overview-card">
        <div class="graphics-overview-label">Total</div>
        <div class="graphics-overview-value">{{ $totalGraphics }}</div>
      </div>
      <div class="graphics-overview-card">
        <div class="graphics-overview-label">Published</div>
        <div class="graphics-overview-value">{{ $statusCounts->get('published', 0) }}</div>
      </div>
      <div class="graphics-overview-card">
        <div class="graphics-overview-label">Drafts</div>
        <div class="graphics-overview-value">{{ $statusCounts->get('draft', 0) }}</div>
      </div>
      <div class="graphics-overview-card">
        <div class="graphics-overview-label">Review</div>
        <div class="graphics-overview-value">{{ $statusCounts->get('review', 0) }}</div>
      </div>
    </div>

    <div class="graphics-status-tabs" aria-label="Graphic status filters">
      <a href="{{ route('admin.graphics.index', request()->only('search')) }}" class="graphics-status-tab @if(! request('status')) is-active @endif">
        All <span>{{ $totalGraphics }}</span>
      </a>
      @foreach($statuses as $status)
        <a href="{{ route('admin.graphics.index', array_filter(['search' => request('search'), 'status' => $status])) }}" class="graphics-status-tab @if(request('status') === $status) is-active @endif">
          {{ ucfirst($status) }} <span>{{ $statusCounts->get($status, 0) }}</span>
        </a>
      @endforeach
    </div>

    <div class="graphics-toolbar">
      <form method="GET" class="graphics-filter">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" placeholder="Search graphics">
        <select name="status" class="admin-select">
          <option value="">All statuses</option>
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
        @if(request()->filled('search') || request()->filled('status'))
          <a href="{{ route('admin.graphics.index') }}" class="admin-btn-secondary">Reset</a>
        @endif
      </form>
      <a href="{{ route('admin.graphics.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Graphic</a>
    </div>

    @if($graphics->count())
      <div class="graphics-table-wrap">
        <table class="admin-table graphics-table">
          <thead>
            <tr>
              <th>Graphic</th>
              <th>Source</th>
              <th>Status</th>
              <th>Sort Order</th>
              <th>Published</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($graphics as $graphic)
              <tr>
                <td>
                  <div class="graphics-title-cell">
                    <div class="graphics-thumb">
                      <img src="{{ $graphic->imageUrl() }}" alt="{{ $graphic->alt_text ?: $graphic->title }}">
                    </div>
                    <div class="graphics-title-copy">
                      <a href="{{ route('admin.graphics.edit', $graphic) }}">{{ $graphic->title }}</a>
                      <div class="graphics-description">{{ Str::limit($graphic->description, 110) ?: 'No description added.' }}</div>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="graphics-source">
                    <i class="bi {{ $graphic->media_asset_id ? 'bi-folder2-open' : 'bi-upload' }}"></i>
                    {{ $graphic->media_asset_id ? 'Media Library' : 'Uploaded file' }}
                  </span>
                </td>
                <td><span class="admin-badge {{ $graphic->status }}">{{ ucfirst($graphic->status) }}</span></td>
                <td><span class="graphics-order-pill"><i class="bi bi-list-ol"></i> {{ $graphic->sort_order }}</span></td>
                <td>
                  <div class="small text-muted">Date</div>
                  <strong>{{ $graphic->published_at?->format('M j, Y') ?? 'Not set' }}</strong>
                </td>
                <td class="text-end">
                  <a href="{{ route('admin.graphics.edit', $graphic) }}" class="admin-btn-secondary"><i class="bi bi-pencil"></i> Edit</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
        <div class="graphics-empty">
          <i class="bi bi-palette"></i>
          <div>
            <h2 class="h5 fw-bold mb-1 text-success">No graphics yet</h2>
            <p class="text-muted mb-0">Add artwork to populate the public graphics page.</p>
          </div>
          <a href="{{ route('admin.graphics.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Graphic</a>
        </div>
    @endif

    <div class="mt-3">{{ $graphics->links() }}</div>
  </section>
@endsection
