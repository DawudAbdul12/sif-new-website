@extends('admin.layouts.app')

@section('title', $typeLabel)
@section('page_title', $typeLabel)
@section('page_subtitle', 'Manage repository documents, metadata, and downloadable files.')

@section('content')
  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 260px" placeholder="Search documents">
        <select name="status" class="admin-select" style="width: 180px">
          <option value="">All statuses</option>
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit">Filter</button>
      </form>

      <a href="{{ route('admin.documents.create', $type) }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Document</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Year</th>
          <th>Date</th>
          <th>File</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($documents as $document)
          <tr>
            <td>
              <div class="fw-bold">{{ $document->title }}</div>
              <div class="small text-muted">{{ Str::limit($document->brief_description, 90) }}</div>
              @if($document->counterparty)
                <div class="small text-muted">Counterparty: {{ $document->counterparty }}</div>
              @endif
            </td>
            <td>{{ $document->fiscal_year ?: '-' }}</td>
            <td>{{ $document->document_date?->format('M j, Y') ?? '-' }}</td>
            <td>{{ $document->file_name ?: 'No file' }}</td>
            <td><span class="admin-badge {{ $document->status }}">{{ ucfirst($document->status) }}</span></td>
            <td class="text-end">
              <a href="{{ route('admin.documents.edit', [$type, $document]) }}" class="admin-btn-secondary">Edit</a>
            </td>
          </tr>
        @empty
          @include('admin.partials.empty-table', [
            'colspan' => 6,
            'icon' => 'bi-file-earmark-lock',
            'title' => request()->query() ? 'No documents match your filters' : 'No documents yet',
            'message' => request()->query() ? 'Try another title, metadata term, or publishing status.' : 'Upload repository documents with dates, descriptions, and publishing controls.',
            'actionLabel' => 'New Document',
            'actionUrl' => route('admin.documents.create', $type),
            'resetUrl' => route('admin.documents.index', $type),
          ])
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">{{ $documents->links() }}</div>
  </section>
@endsection
