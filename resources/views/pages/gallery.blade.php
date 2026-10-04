@extends('layouts.app')

@section('title', 'Gallery - Social Investment Fund')

@section('content')
<main class="page-main">
  <section class="page-hero">
    <div class="container"><span class="eyebrow">Media</span><h1>Gallery</h1></div>
  </section>

  <section class="sec bg-white">
    <div class="container">
      <div class="news-grid">
        @forelse($albums as $album)
          <article class="news-card reveal gallery-modal">
            <div class="news-img"><img src="{{ $album->coverUrl() }}" alt="{{ $album->title }}" loading="lazy"></div>
            <div class="news-card-body">
              <h3>{{ $album->title }}</h3>
              @if($album->description)<p>{{ $album->description }}</p>@endif
              @foreach($album->images as $image)
                <img src="{{ $image->url() }}" alt="{{ $image->alt_text ?: $album->title }}" loading="lazy" style="width:72px;height:54px;object-fit:cover;margin:4px;border-radius:6px;">
              @endforeach
            </div>
          </article>
        @empty
          <p>No gallery albums have been published yet.</p>
        @endforelse
      </div>
      @include('partials.cms-pagination-summary', ['paginator' => $albums])
    </div>
  </section>
</main>
@endsection
