@extends('layouts.app')

@section('title', 'Press Releases - Social Investment Fund')

@section('content')
<main class="page-main">
  <section class="page-hero">
    <div class="container"><span class="eyebrow">News &amp; Media</span><h1>Press Releases</h1></div>
  </section>

  <section class="sec bg-white">
    <div class="container">
      <div class="news-grid">
        @forelse($pressReleases as $release)
          <article class="news-card reveal">
            <div class="news-card-body">
              <span class="news-meta">{{ $release->published_at?->format('F j, Y') }}</span>
              <h3>{{ $release->title }}</h3>
              @if($release->brief_description)<p>{{ $release->brief_description }}</p>@endif
              @if($release->body)<div>{!! $release->body !!}</div>@endif
              @if($release->fileUrl())<a class="news-read" href="{{ $release->fileUrl() }}" target="_blank" rel="noopener">View or Download</a>@endif
            </div>
          </article>
        @empty
          <p>No press releases have been published yet.</p>
        @endforelse
      </div>
      @include('partials.cms-pagination-summary', ['paginator' => $pressReleases])
    </div>
  </section>
</main>
@endsection
