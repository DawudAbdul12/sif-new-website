@extends('admin.layouts.app')

@section('title', 'Activity Log')
@section('page_title', 'Activity Log')
@section('page_subtitle', 'Review create, update, and delete activity across the CMS.')

@push('styles')
  <style>
    .activity-toolbar {
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      margin-bottom: 18px;
      padding: 14px;
    }

    .activity-summary-grid {
      display: grid;
      gap: 12px;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      margin-bottom: 18px;
    }

    .activity-summary-card {
      background: #fbfcf8;
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      padding: 14px;
    }

    .activity-summary-label {
      color: var(--gb-muted);
      font-size: .72rem;
      font-weight: 800;
      text-transform: uppercase;
    }

    .activity-summary-value {
      color: var(--gb-green);
      font-size: 1.45rem;
      font-weight: 900;
      line-height: 1.1;
      margin-top: 7px;
    }

    .activity-filter {
      display: grid;
      gap: 10px;
      grid-template-columns: minmax(240px, 1.4fr) 140px minmax(200px, 1fr) 120px 145px 145px;
    }

    .activity-filter-actions {
      align-items: center;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      grid-column: 1 / -1;
      margin-top: 12px;
    }

    .activity-table-wrap {
      background: #fff;
      border: 0;
      border-radius: 8px;
      box-shadow: 0 .5rem 1.25rem rgba(15, 23, 42, .08);
      overflow: hidden;
      padding: 16px;
    }

    .activity-table {
      border-collapse: separate;
      border-spacing: 0;
      table-layout: fixed;
      width: 100%;
      margin: 0;
    }

    .activity-table th:nth-child(1),
    .activity-table td:nth-child(1) {
      width: 52px;
    }

    .activity-table th:nth-child(2),
    .activity-table td:nth-child(2) {
      width: 22%;
    }

    .activity-table th:nth-child(3),
    .activity-table td:nth-child(3) {
      width: 120px;
    }

    .activity-table th:nth-child(5),
    .activity-table td:nth-child(5) {
      width: 150px;
    }

    .activity-table th:nth-child(6),
    .activity-table td:nth-child(6) {
      width: 92px;
    }

    .activity-table thead th {
      background: #f8faf9;
      border-bottom: 1px solid var(--gb-line);
      color: var(--gb-muted);
      font-size: .76rem;
      font-weight: 900;
      letter-spacing: 0;
      padding: 14px 12px;
      text-transform: uppercase;
    }

    .activity-table tbody td {
      border-bottom: 1px solid #edf1ea;
      padding: 14px 12px;
      vertical-align: middle;
      overflow-wrap: anywhere;
    }

    .activity-table tbody tr:hover {
      background: #fbfcf8;
    }

    .activity-table tbody tr:last-child td {
      border-bottom: 0;
    }

    .activity-row-number {
      color: var(--gb-muted);
      font-size: .84rem;
      font-weight: 800;
      width: 52px;
    }

    .activity-chip {
      align-items: center;
      background: #f0fdf4;
      border: 1px solid #dcfce7;
      border-radius: 999px;
      color: #166534;
      display: inline-flex;
      font-size: .75rem;
      font-weight: 800;
      line-height: 1;
      padding: 6px 10px;
      text-transform: capitalize;
    }

    .activity-subject {
      color: var(--gb-ink);
      font-weight: 850;
      max-width: 270px;
      overflow-wrap: anywhere;
    }

    .activity-muted {
      color: var(--gb-muted);
      font-size: .8rem;
      font-weight: 700;
    }

    .activity-url {
      color: var(--gb-muted);
      font-size: .76rem;
      max-width: 260px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .activity-changes {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: 8px;
      max-width: 500px;
    }

    .activity-change-pill {
      background: #edf5ef;
      border-radius: 999px;
      color: var(--gb-green);
      font-size: .72rem;
      font-weight: 800;
      padding: 5px 8px;
    }

    .activity-method-pill {
      background: #fff8e4;
      border: 1px solid #eadcaa;
      border-radius: 999px;
      color: #6d5614;
      display: inline-flex;
      font-size: .72rem;
      font-weight: 900;
      line-height: 1;
      padding: 6px 8px;
    }

    .activity-summary-text {
      color: var(--gb-ink);
      font-size: .88rem;
      max-width: 500px;
    }

    .activity-action-link {
      align-items: center;
      border: 1px solid var(--gb-green);
      border-radius: 6px;
      color: var(--gb-green);
      display: inline-flex;
      font-size: .82rem;
      font-weight: 800;
      gap: 6px;
      justify-content: center;
      padding: 7px 11px;
      text-decoration: none;
      white-space: nowrap;
    }

    .activity-action-link:hover {
      background: var(--gb-green);
      color: #fff;
    }

    .activity-empty {
      padding: 42px 18px;
      text-align: center;
    }

    @media (max-width: 1199.98px) {
      .activity-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 980px) {
      .activity-filter {
        grid-template-columns: 1fr;
        width: 100%;
      }

      .activity-table-wrap {
        padding: 10px;
      }

      .activity-table th:nth-child(3),
      .activity-table td:nth-child(3) {
        display: none;
      }

      .activity-table th:nth-child(2),
      .activity-table td:nth-child(2) {
        width: 28%;
      }

      .activity-table th:nth-child(5),
      .activity-table td:nth-child(5) {
        width: 128px;
      }
    }

    @media (max-width: 575.98px) {
      .activity-summary-grid {
        grid-template-columns: 1fr;
      }

      .activity-table th:nth-child(1),
      .activity-table td:nth-child(1),
      .activity-table th:nth-child(5),
      .activity-table td:nth-child(5) {
        display: none;
      }

      .activity-table th:nth-child(2),
      .activity-table td:nth-child(2) {
        width: 34%;
      }

      .activity-table th:nth-child(6),
      .activity-table td:nth-child(6) {
        width: 58px;
      }

      .activity-table thead th,
      .activity-table tbody td {
        padding: 12px 8px;
      }

      .activity-muted {
        font-size: .74rem;
      }

      .activity-action-link {
        gap: 0;
        padding: 8px;
      }

      .activity-action-link span {
        display: none;
      }
    }
  </style>
@endpush

@section('content')
  <section class="admin-panel">
    <div class="activity-summary-grid">
      <div class="activity-summary-card">
        <div class="activity-summary-label">Visible Logs</div>
        <div class="activity-summary-value">{{ number_format($summary['total']) }}</div>
      </div>
      <div class="activity-summary-card">
        <div class="activity-summary-label">Created</div>
        <div class="activity-summary-value">{{ number_format($summary['created']) }}</div>
      </div>
      <div class="activity-summary-card">
        <div class="activity-summary-label">Updated</div>
        <div class="activity-summary-value">{{ number_format($summary['updated']) }}</div>
      </div>
      <div class="activity-summary-card">
        <div class="activity-summary-label">Deleted</div>
        <div class="activity-summary-value">{{ number_format($summary['deleted']) }}</div>
      </div>
      <div class="activity-summary-card">
        <div class="activity-summary-label">Login / Logout</div>
        <div class="activity-summary-value">{{ number_format($summary['auth']) }}</div>
      </div>
    </div>

    <div class="activity-toolbar">
      <form method="GET" class="activity-filter">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" placeholder="Search subject, user, IP, URL">
        <select name="action" class="admin-select">
          <option value="">All actions</option>
          @foreach(['created', 'updated', 'deleted', 'restored', 'permanently_deleted', 'logged_in', 'logged_out'] as $action)
            <option value="{{ $action }}" @selected(request('action') === $action)>{{ Str::headline($action) }}</option>
          @endforeach
        </select>
        <select name="subject_type" class="admin-select">
          <option value="">All models</option>
          @foreach($subjectTypes as $subjectType)
            <option value="{{ $subjectType }}" @selected(request('subject_type') === $subjectType)>{{ class_basename($subjectType) }}</option>
          @endforeach
        </select>
        <select name="request_method" class="admin-select">
          <option value="">Method</option>
          @foreach(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'] as $method)
            <option value="{{ $method }}" @selected(request('request_method') === $method)>{{ $method }}</option>
          @endforeach
        </select>
        <input type="date" name="from_date" value="{{ request('from_date') }}" class="admin-control" aria-label="From date">
        <input type="date" name="to_date" value="{{ request('to_date') }}" class="admin-control" aria-label="To date">
        <div class="activity-filter-actions">
          <button class="admin-btn-secondary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
          @if(request()->filled('search') || request()->filled('action') || request()->filled('subject_type') || request()->filled('request_method') || request()->filled('from_date') || request()->filled('to_date'))
            <a href="{{ route('admin.activity-logs.index') }}" class="admin-btn-secondary"><i class="bi bi-x-lg"></i> Reset</a>
          @endif
        </div>
      </form>
    </div>

    <div class="activity-table-wrap">
      <table class="table align-middle activity-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Subject</th>
            <th>Activity</th>
            <th>What Happened</th>
            <th>When</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($activityLogs as $activity)
            <tr>
              <td class="activity-row-number">
                {{ $loop->iteration + ($activityLogs->currentPage() - 1) * $activityLogs->perPage() }}
              </td>
              <td>
                @if($activity->subjectAdminUrl())
                  <a href="{{ $activity->subjectAdminUrl() }}" class="activity-subject text-decoration-none">{{ $activity->subject_label ?: 'Untitled' }}</a>
                @else
                  <div class="activity-subject">{{ $activity->subject_label ?: 'Untitled' }}</div>
                @endif
                <div class="activity-muted">ID: {{ $activity->subject_id ?? 'n/a' }}</div>
                <div class="activity-muted">{{ $activity->subjectName() }}</div>
              </td>
              <td>
                <span class="activity-chip">{{ $activity->actionLabel() }}</span>
                <div class="activity-muted mt-2">{{ $activity->subjectName() }}</div>
              </td>
              <td>
                <div class="activity-summary-text">
                  {{ $activity->causer?->name ?? $activity->causer_name ?? 'System' }} {{ $activity->action }} {{ $activity->subjectName() }} record.
                </div>
                <div class="activity-muted mt-1">
                  {{ $activity->requestMethodLabel() }} @if($activity->ip_address) &bull; {{ $activity->ip_address }} @endif
                </div>
                <div class="activity-changes">
                  @forelse($activity->changedAttributeLabels() as $attribute)
                    <span class="activity-change-pill">{{ $attribute }}</span>
                  @empty
                    <span class="activity-muted">{{ $activity->action === 'created' ? 'New record' : ($activity->action === 'deleted' ? 'Record removed' : 'No tracked fields') }}</span>
                  @endforelse
                </div>
              </td>
              <td>
                <div class="activity-muted">{{ $activity->created_at?->diffForHumans() }}</div>
                <div class="activity-muted mt-1">{{ $activity->created_at?->format('d M Y, H:i') }}</div>
              </td>
              <td class="text-end">
                <a href="{{ route('admin.activity-logs.show', $activity) }}" class="activity-action-link"><i class="bi bi-eye"></i> <span>View</span></a>
              </td>
            </tr>
          @empty
            @include('admin.partials.empty-table', [
              'colspan' => 6,
              'icon' => 'bi-activity',
              'title' => request()->query() ? 'No activity matches your filters' : 'No activity recorded yet',
              'message' => request()->query() ? 'Try another action, model type, method, date range, user, IP, or URL.' : 'Create, update, delete, and restore actions will appear here as the CMS is used.',
              'resetUrl' => route('admin.activity-logs.index'),
            ])
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">@include('partials.compact-pagination', ['paginator' => $activityLogs])</div>
  </section>
@endsection
