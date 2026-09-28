@extends('admin.layouts.app')

@section('title', 'License Registry')
@section('page_title', 'License Registry')
@section('page_subtitle', 'Manage licensed businesses and import official registry CSV files.')

@push('styles')
  <style>
    .registry-import-panel {
      background: #f8fbf7;
      border: 1px solid rgba(24, 72, 45, 0.12);
      border-radius: 8px;
      padding: 18px;
    }

    .registry-import-help {
      color: var(--gb-muted);
      font-size: 0.84rem;
      font-weight: 600;
    }
  </style>
@endpush

@section('content')
  <section class="admin-panel mb-3">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
      <div>
        <h2 class="h5 fw-bold text-success mb-1">Import Registry CSV</h2>
        <p class="registry-import-help mb-0">Expected headers: SNO, REGISTERED BUSINESS NAME, LICENSE CERTIFICATE NUMBER, ISSUED DATE, EXPIRY DATE.</p>
      </div>
      <form method="POST" action="{{ route('admin.license-registry.import') }}" enctype="multipart/form-data" class="d-flex flex-wrap gap-2">
        @csrf
        <select name="category" class="admin-select" style="width: 260px" required>
          @foreach($categories as $key => $label)
            <option value="{{ $key }}" @selected(request('category', 'buyerTier2') === $key)>{{ $label }}</option>
          @endforeach
        </select>
        <input type="file" name="file" class="admin-control" style="width: 300px" accept=".csv,text/csv" required>
        <button type="submit" class="admin-btn"><i class="bi bi-cloud-arrow-up"></i> Import CSV</button>
      </form>
    </div>

    @if(session('import_errors'))
      <div class="alert alert-warning mt-3 mb-0">
        <div class="fw-bold mb-1">Import warnings</div>
        @foreach(session('import_errors') as $error)
          <div class="small">{{ $error }}</div>
        @endforeach
      </div>
    @endif
  </section>

  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 240px" placeholder="Search business or certificate">
        <select name="category" class="admin-select" style="width: 240px">
          <option value="">All categories</option>
          @foreach($categories as $key => $label)
            <option value="{{ $key }}" @selected(request('category') === $key)>{{ $label }}</option>
          @endforeach
        </select>
        <select name="status" class="admin-select" style="width: 150px">
          <option value="">All statuses</option>
          @foreach(['active', 'inactive'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <select name="per_page" class="admin-select" style="width: 140px">
          @foreach([25, 50, 100, 200] as $size)
            <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }} / page</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
      </form>
      <a href="{{ route('admin.license-registry.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Entry</a>
    </div>

    <div class="small fw-bold text-muted mb-3">
      @if($entries->total())
        Showing {{ $entries->firstItem() }}-{{ $entries->lastItem() }} of {{ $entries->total() }} registry entries
      @else
        No registry entries found
      @endif
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>SNO</th>
          <th>Business</th>
          <th>Certificate</th>
          <th>Category</th>
          <th>Issued</th>
          <th>Expiry</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($entries as $entry)
          <tr>
            <td>{{ $entry->registry_number ?: '-' }}</td>
            <td>
              <div class="fw-bold">{{ $entry->business_name }}</div>
              @if($entry->notes)
                <div class="small text-muted">{{ Str::limit($entry->notes, 80) }}</div>
              @endif
            </td>
            <td>{{ $entry->certificate_number }}</td>
            <td>{{ $entry->categoryLabel() }}</td>
            <td>{{ $entry->issued_date?->format('M j, Y') ?? '-' }}</td>
            <td>{{ $entry->expiry_date?->format('M j, Y') ?? '-' }}</td>
            <td><span class="admin-badge {{ $entry->status === 'inactive' ? 'archived' : '' }}">{{ ucfirst($entry->status) }}</span></td>
            <td class="text-end"><a href="{{ route('admin.license-registry.edit', $entry) }}" class="admin-btn-secondary">Edit</a></td>
          </tr>
        @empty
          @include('admin.partials.empty-table', [
            'colspan' => 8,
            'icon' => 'bi-patch-check',
            'title' => request()->query() ? 'No registry entries match your filters' : 'No registry entries yet',
            'message' => request()->query() ? 'Try another business name, certificate number, category, status, or page size.' : 'Add a registry entry manually or import an official CSV file to populate this table.',
            'actionLabel' => 'New Entry',
            'actionUrl' => route('admin.license-registry.create'),
            'resetUrl' => route('admin.license-registry.index'),
          ])
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">{{ $entries->links() }}</div>
  </section>
@endsection
