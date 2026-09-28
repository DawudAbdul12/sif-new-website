@php
  $showFooter = $showFooter ?? true;
  $dateAttribute = $dateAttribute ?? 'document_date';
  $displayDate = $document->{$dateAttribute} ?? null;
@endphp

<a href="{{ $document->fileUrl() ?: '#' }}" class="press-card" @if($document->fileUrl()) target="_blank" rel="noopener" @endif>
  @if($displayDate)
    <div class="d-flex justify-content-between align-items-start">
      <span class="press-date">{{ $displayDate->format('F j, Y') }}</span>
      <i class="bi bi-cloud-arrow-down press-icon"></i>
    </div>
  @endif
  <h3 class="contract-title mt-3">{{ $document->title }}</h3>
  @if($showFooter)
    <div class="d-flex justify-content-between align-items-center mt-3">
      <span class="link-gold">View or Download</span>
      <i class="bi bi-cloud-arrow-down press-icon"></i>
    </div>
  @endif
</a>
