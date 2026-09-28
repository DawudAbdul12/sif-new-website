@php
  $colspan = $colspan ?? 1;
  $icon = $icon ?? 'bi-inbox';
  $title = $title ?? 'No records found';
  $message = $message ?? 'Create a new record or adjust your filters to see results here.';
  $actionLabel = $actionLabel ?? null;
  $actionUrl = $actionUrl ?? null;
  $resetUrl = $resetUrl ?? null;
@endphp

<tr>
  <td colspan="{{ $colspan }}" class="admin-empty-cell">
    <div class="admin-empty-state">
      <span class="admin-empty-visual"><i class="bi {{ $icon }}"></i></span>
      <h2 class="admin-empty-title">{{ $title }}</h2>
      <p class="admin-empty-copy">{{ $message }}</p>
      @if($actionLabel || $resetUrl)
        <div class="admin-empty-actions">
          @if($actionLabel && $actionUrl)
            <a href="{{ $actionUrl }}" class="admin-btn"><i class="bi bi-plus-lg"></i> {{ $actionLabel }}</a>
          @endif
          @if($resetUrl && request()->query())
            <a href="{{ $resetUrl }}" class="admin-btn-secondary"><i class="bi bi-x-lg"></i> Clear filters</a>
          @endif
        </div>
      @endif
    </div>
  </td>
</tr>
