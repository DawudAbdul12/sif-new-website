@extends('layouts.app')

@section('title', 'Videos - Social Investment Fund')

@section('content')
<main class="page-main">
  <section class="page-hero">
    <div class="container"><span class="eyebrow">Media</span><h1>Videos</h1></div>
  </section>

  <section class="sec bg-white">
    <div class="container">
      <div class="news-grid">
        @forelse($videos as $video)
          <article class="news-card reveal">
            <div class="news-img">
              @if($video->embed_url)
                <iframe src="{{ $video->embed_url }}" title="{{ $video->title }}" loading="lazy" allowfullscreen style="width:100%;aspect-ratio:16/9;border:0;"></iframe>
              @else
                <img src="{{ $video->thumbnailUrl() }}" alt="{{ $video->title }}" loading="lazy">
              @endif
            </div>
            <div class="news-card-body">
              <h3>{{ $video->title }}</h3>
              @if($video->description)<p>{{ $video->description }}</p>@endif
              @if($video->video_url)<a class="news-read" href="{{ $video->video_url }}" target="_blank" rel="noopener">Watch Video</a>@endif
            </div>
          </article>
        @empty
          <p>No videos have been published yet.</p>
        @endforelse
      </div>
      @include('partials.cms-pagination-summary', ['paginator' => $videos])
    </div>
  </section>
</main>
@endsection
