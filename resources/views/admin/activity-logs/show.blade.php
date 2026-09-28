@extends('admin.layouts.app')

@section('title', 'Activity Detail')
@section('page_title', 'Activity Detail')
@section('page_subtitle', $activityLog->actionLabel().' '.$activityLog->subjectName().' record')

@push('styles')
  <style>
    .activity-detail-header {
      align-items: flex-start;
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      justify-content: space-between;
      margin-bottom: 18px;
      padding: 16px;
    }

    .activity-detail-title {
      color: var(--gb-green);
      font-size: 1.2rem;
      font-weight: 900;
      margin: 0;
    }

    .activity-detail-subtitle {
      color: var(--gb-muted);
      font-size: .9rem;
      font-weight: 700;
      margin-top: 4px;
    }

    .activity-detail-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .activity-detail-grid {
      display: grid;
      gap: 14px;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      margin-bottom: 18px;
    }

    .activity-detail-card {
      background: #fbfcf8;
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      padding: 14px;
    }

    .activity-detail-label {
      color: var(--gb-muted);
      font-size: .72rem;
      font-weight: 800;
      text-transform: uppercase;
    }

    .activity-detail-value {
      color: var(--gb-green);
      font-weight: 800;
      margin-top: 6px;
      overflow-wrap: anywhere;
    }

    .activity-section-title {
      align-items: center;
      color: var(--gb-green);
      display: flex;
      font-size: 1rem;
      font-weight: 900;
      gap: 8px;
      margin: 24px 0 12px;
    }

    .activity-diff-wrap {
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      overflow-x: auto;
    }

    .activity-diff-table {
      margin: 0;
      min-width: 920px;
    }

    .activity-diff-table pre {
      background: #fbfcf8;
      border: 1px solid var(--gb-line);
      border-radius: 6px;
      color: var(--gb-ink);
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
      font-size: .78rem;
      margin: 0;
      max-height: 220px;
      overflow: auto;
      padding: 10px;
      white-space: pre-wrap;
      word-break: break-word;
    }

    .activity-diff-table td:first-child {
      min-width: 180px;
      vertical-align: top;
    }

    .activity-request-card {
      background: #fff;
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      display: grid;
      gap: 10px;
      margin-top: 18px;
      padding: 16px;
    }

    .activity-request-row {
      display: grid;
      gap: 8px;
      grid-template-columns: 140px minmax(0, 1fr);
    }

    .activity-request-row strong {
      color: var(--gb-green);
      font-size: .82rem;
    }

    .activity-request-row span {
      color: var(--gb-muted);
      overflow-wrap: anywhere;
    }

    .activity-action-mark {
      background: #edf5ef;
      border-radius: 999px;
      color: var(--gb-green);
      display: inline-flex;
      font-size: .75rem;
      font-weight: 900;
      line-height: 1;
      margin-bottom: 10px;
      padding: 7px 10px;
      text-transform: uppercase;
    }

    @media (max-width: 991.98px) {
      .activity-detail-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 575.98px) {
      .activity-detail-grid,
      .activity-request-row {
        grid-template-columns: 1fr;
      }
    }
  </style>
@endpush

@section('content')
  <section class="admin-panel">
    <div class="activity-detail-header">
      <div>
        <span class="activity-action-mark">{{ $activityLog->actionLabel() }}</span>
        <h2 class="activity-detail-title">{{ $activityLog->subject_label ?: 'Untitled record' }}</h2>
        <div class="activity-detail-subtitle">
          {{ $activityLog->subjectName() }} record by {{ $activityLog->causer?->name ?? $activityLog->causer_name ?? 'System' }}
        </div>
      </div>
      <div class="activity-detail-actions">
        <a href="{{ route('admin.activity-logs.index') }}" class="admin-btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
        @if($activityLog->subjectAdminUrl())
          <a href="{{ $activityLog->subjectAdminUrl() }}" class="admin-btn"><i class="bi bi-box-arrow-up-right"></i> Open Record</a>
        @endif
      </div>
    </div>

    <div class="activity-detail-grid">
      <div class="activity-detail-card">
        <div class="activity-detail-label">Action</div>
        <div class="activity-detail-value">{{ $activityLog->actionLabel() }}</div>
      </div>
      <div class="activity-detail-card">
        <div class="activity-detail-label">Model</div>
        <div class="activity-detail-value">{{ $activityLog->subjectName() }}</div>
      </div>
      <div class="activity-detail-card">
        <div class="activity-detail-label">Subject</div>
        <div class="activity-detail-value">{{ $activityLog->subject_label ?: 'Untitled' }}</div>
      </div>
      <div class="activity-detail-card">
        <div class="activity-detail-label">When</div>
        <div class="activity-detail-value">{{ $activityLog->created_at?->format('M j, Y g:i A') }}</div>
      </div>
      <div class="activity-detail-card">
        <div class="activity-detail-label">User</div>
        <div class="activity-detail-value">{{ $activityLog->causer?->name ?? $activityLog->causer_name ?? 'System' }}</div>
      </div>
      <div class="activity-detail-card">
        <div class="activity-detail-label">Email</div>
        <div class="activity-detail-value">{{ $activityLog->causer?->email ?? $activityLog->causer_email ?? 'n/a' }}</div>
      </div>
      <div class="activity-detail-card">
        <div class="activity-detail-label">IP Address</div>
        <div class="activity-detail-value">{{ $activityLog->ip_address ?: 'n/a' }}</div>
      </div>
      <div class="activity-detail-card">
        <div class="activity-detail-label">Method</div>
        <div class="activity-detail-value">{{ $activityLog->requestMethodLabel() }}</div>
      </div>
      <div class="activity-detail-card">
        <div class="activity-detail-label">Record ID</div>
        <div class="activity-detail-value">{{ $activityLog->subject_id ?? 'n/a' }}</div>
      </div>
    </div>

    <h2 class="activity-section-title"><i class="bi bi-columns-gap"></i> Changed Values</h2>
    <div class="activity-diff-wrap">
      <table class="admin-table activity-diff-table">
        <thead>
          <tr>
            <th>Field</th>
            <th>Before</th>
            <th>After</th>
          </tr>
        </thead>
        <tbody>
          @forelse($activityLog->changedRows() as $row)
            <tr>
              <td class="fw-bold">{{ $row['label'] }}</td>
              <td><pre>{{ $row['old'] }}</pre></td>
              <td><pre>{{ $row['new'] }}</pre></td>
            </tr>
          @empty
            @include('admin.partials.empty-table', [
              'colspan' => 3,
              'icon' => 'bi-columns-gap',
              'title' => 'No changed values captured',
              'message' => 'This activity did not include tracked field changes, or the action was recorded without before-and-after values.',
            ])
          @endforelse
        </tbody>
      </table>
    </div>

    <h2 class="activity-section-title"><i class="bi bi-router"></i> Request Context</h2>
    <div class="activity-request-card">
      <div class="activity-request-row">
        <strong>URL</strong>
        <span>{{ $activityLog->url ?: 'n/a' }}</span>
      </div>
      <div class="activity-request-row">
        <strong>User Agent</strong>
        <span>{{ $activityLog->browserLabel() }}</span>
      </div>
      @foreach($activityLog->metadataRows() as $row)
        <div class="activity-request-row">
          <strong>{{ $row['label'] }}</strong>
          <span>{{ $row['value'] }}</span>
        </div>
      @endforeach
    </div>
  </section>
@endsection
