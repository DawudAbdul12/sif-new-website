@php
  $href = $document->fileUrl() ?: '#';
  $kind = $document->file_mime_type === 'application/pdf' ? 'PDF' : $document->typeLabel();
  $meta = $document->fiscal_year ?: $document->typeLabel();
@endphp

<a class="doc-card reveal" style="--i:{{ $index ?? 0 }}" href="{{ $href }}" @if($href !== '#') target="_blank" rel="noopener" @endif>
  <div class="doc-top">
    <div class="doc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 4h12l4 4v12H4z"/><path d="M16 4v4h4"/></svg></div>
    <span class="doc-type">{{ $kind }}</span>
  </div>
  <h4>{{ $document->title }}</h4>
  <div class="doc-meta"><span>{{ $meta }}</span>@if($document->file_name)<span>{{ pathinfo($document->file_name, PATHINFO_EXTENSION) ?: 'Link' }}</span>@endif</div>
  <span class="doc-action">View or Download <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v9m0 0 3.5-3.5M8 11 4.5 7.5M3 13.5h10"/></svg></span>
</a>
