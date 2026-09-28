@php
  $recordActivitySubject = $recordActivitySubject ?? null;
  $recordActivityHeading = $recordActivityHeading ?? 'Activity Log';
  $recordActivityType = $recordActivityType ?? ($recordActivitySubject ? get_class($recordActivitySubject) : null);
  $recordActivityId = $recordActivityId ?? $recordActivitySubject?->getKey();
  $recordActivityLogs = $recordActivityLogs ?? (
    $recordActivityType
      ? \App\Models\ActivityLog::query()
        ->with('causer:id,name,email')
        ->where('subject_type', $recordActivityType)
        ->when($recordActivityId, fn ($query) => $query->where('subject_id', $recordActivityId))
        ->recent()
        ->limit(8)
        ->get()
      : collect()
  );
@endphp

@once
  @push('styles')
    <style>
      .record-activity-heading {
        align-items: center;
        display: flex;
        gap: 12px;
        justify-content: space-between;
        margin-bottom: 16px;
      }

      .record-activity-title {
        align-items: center;
        color: var(--gb-green);
        display: flex;
        font-size: .95rem;
        font-weight: 900;
        gap: 9px;
        margin: 0;
      }

      .record-activity-title i {
        color: var(--gb-gold);
      }

      .record-activity-list {
        display: grid;
        gap: 10px;
      }

      .record-activity-item {
        align-items: flex-start;
        border: 1px solid var(--gb-line);
        border-radius: 8px;
        color: inherit;
        display: grid;
        gap: 10px;
        grid-template-columns: 34px minmax(0, 1fr);
        padding: 11px;
        text-decoration: none;
      }

      .record-activity-item:hover {
        background: #fbfcf8;
        border-color: rgba(23, 71, 45, 0.2);
      }

      .record-activity-icon {
        align-items: center;
        background: #edf5ef;
        border-radius: 8px;
        color: var(--gb-green);
        display: inline-flex;
        height: 34px;
        justify-content: center;
        width: 34px;
      }

      .record-activity-icon.review {
        background: #fff8e4;
        color: #725600;
      }

      .record-activity-icon.archived {
        background: #f5eeee;
        color: #8a2f2f;
      }

      .record-activity-body,
      .record-activity-action,
      .record-activity-meta,
      .record-activity-fields {
        display: block;
        min-width: 0;
      }

      .record-activity-action {
        color: var(--gb-green);
        font-size: .88rem;
        font-weight: 900;
      }

      .record-activity-meta,
      .record-activity-fields {
        color: var(--gb-muted);
        font-size: .78rem;
        font-weight: 700;
        margin-top: 3px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }

      .record-activity-empty {
        align-items: center;
        border: 1px dashed var(--gb-line);
        border-radius: 8px;
        color: var(--gb-muted);
        display: flex;
        gap: 10px;
        padding: 14px;
        font-size: .84rem;
        font-weight: 700;
      }
    </style>
  @endpush
@endonce

<div class="admin-panel mt-3">
  <div class="record-activity-heading">
    <h2 class="record-activity-title"><i class="bi bi-clock-history"></i> {{ $recordActivityHeading }}</h2>
    @if($recordActivityType)
      <a href="{{ route('admin.activity-logs.index', array_filter(['subject_type' => $recordActivityType, 'subject_id' => $recordActivityId])) }}" class="small fw-bold text-success text-decoration-none">View all</a>
    @endif
  </div>

  <div class="record-activity-list">
    @forelse($recordActivityLogs as $activity)
      <a href="{{ route('admin.activity-logs.show', $activity) }}" class="record-activity-item">
        <span class="record-activity-icon {{ $activity->actionBadgeClass() }}">
          <i class="bi {{ $activity->action === 'created' ? 'bi-plus-lg' : (in_array($activity->action, ['deleted', 'permanently_deleted'], true) ? 'bi-trash' : ($activity->action === 'restored' ? 'bi-arrow-counterclockwise' : 'bi-pencil')) }}"></i>
        </span>
        <span class="record-activity-body">
          <span class="record-activity-action">{{ $activity->actionLabel() }}</span>
          <span class="record-activity-meta">{{ $activity->causer?->name ?? $activity->causer_name ?? 'System' }} · {{ $activity->created_at?->diffForHumans() }}</span>
          @if($activity->changedAttributeLabels())
            <span class="record-activity-fields">{{ implode(', ', array_slice($activity->changedAttributeLabels(), 0, 4)) }}</span>
          @endif
        </span>
      </a>
    @empty
      <div class="record-activity-empty">
        <i class="bi bi-clock-history"></i>
        <span>No activity has been recorded for this record yet.</span>
      </div>
    @endforelse
  </div>
</div>
