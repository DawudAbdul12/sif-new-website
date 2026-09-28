<article class="news-card">
  <a href="{{ $post->publicUrl() }}" class="news-card-img-link">
    <div class="news-card-img news-card-img-logo">
      <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}">
    </div>
  </a>
  <div class="news-card-body">
    <span class="news-tag">{{ $post->categoryRelation?->name ?? ucfirst($post->type) }}</span>
    <h3><a href="{{ $post->publicUrl() }}" class="news-card-title-link">{{ $post->title }}</a></h3>
    <a href="{{ $post->publicUrl() }}" class="link-gold">Read More</a>
  </div>
</article>
