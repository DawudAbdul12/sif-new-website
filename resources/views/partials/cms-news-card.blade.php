@php
  $categoryLabel = $post->categoryRelation?->name ?? $post->category ?? ucfirst($post->type);
  $dateLabel = $post->published_at?->format('F Y');
  $excerpt = $post->excerpt ?: str($post->body)->stripTags()->limit(140)->toString();
@endphp

<a class="news-card {{ ($featured ?? false) ? 'news-feature' : '' }} reveal" style="--i:{{ $index ?? 0 }}" href="{{ $post->publicUrl() }}">
  <div class="nimg">
    <span class="ncat">{{ $categoryLabel }}</span>
    <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" data-fallback loading="lazy">
  </div>
  <div class="nbody">
    @if($dateLabel)<span class="ndate">{{ $dateLabel }}</span>@endif
    <h3>{{ $post->title }}</h3>
    <p>{{ $excerpt }}</p>
    <span class="nmore">Read more <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></span>
  </div>
</a>
