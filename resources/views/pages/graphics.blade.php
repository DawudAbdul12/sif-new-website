@extends('layouts.app')

@section('title', 'Graphics - Social Investment Fund')

@section('content')
<main class="page-main">
  <section class="page-hero">
    <div class="container"><span class="eyebrow">Media</span><h1>Graphics</h1></div>
  </section>

  <section class="sec bg-white">
    <div class="container">
      <div class="news-grid">
        @forelse($graphics as $graphic)
          <article class="news-card reveal">
            <div class="news-img"><img src="{{ $graphic->imageUrl() }}" alt="{{ $graphic->alt_text ?: $graphic->title }}" loading="lazy"></div>
            <div class="news-card-body">
              <h3>{{ $graphic->title }}</h3>
              @if($graphic->description)<p>{{ $graphic->description }}</p>@endif
            </div>
          </article>
        @empty
          <p>No graphics have been published yet.</p>
        @endforelse
      </div>
      @include('partials.cms-pagination-summary', ['paginator' => $graphics])
    </div>
  </section>
</main>
@endsection
