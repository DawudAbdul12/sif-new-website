<a href="{{ $item->fileUrl() ?: '#' }}" class="press-card" @if($item->fileUrl()) target="_blank" rel="noopener" @endif>
  <div class="d-flex justify-content-between align-items-start">
    <span class="press-date">{{ $item->published_at?->format('F j, Y') ?? $item->created_at?->format('F j, Y') }}</span>
    <i class="bi bi-cloud-arrow-down press-icon"></i>
  </div>
  <p class="press-title">{{ $item->title }}</p>
</a>
