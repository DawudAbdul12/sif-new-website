@extends('layouts.app')

@section('title', $title.' - Social Investment Fund')

@section('content')
<main class="page-main">
  <section class="page-hero">
    <div class="container"><span class="eyebrow">Resource Centre</span><h1>{{ $title }}</h1></div>
  </section>

  <section class="sec bg-white">
    <div class="container">
      <div class="doc-grid">
        @forelse($documents as $index => $document)
          @if($type === 'audited-financial-statements')
            <article class="doc-card reveal" style="--i:{{ $index }}">
              <div class="doc-top">
                <span class="doc-type">{{ $document->typeLabel() }}</span>
              </div>
              <h4>{{ $document->title }}</h4>
              @if($document->published_at)
                <div class="doc-meta"><span>{{ $document->published_at->format('F j, Y') }}</span></div>
              @endif
              @if($document->brief_description)<p>{{ $document->brief_description }}</p>@endif
            </article>
          @else
            @include('partials.cms-doc-card', ['document' => $document, 'index' => $index])
          @endif
        @empty
          <p>No {{ strtolower($title) }} have been published yet.</p>
        @endforelse
      </div>
      @include('partials.cms-pagination-summary', ['paginator' => $documents])
    </div>
  </section>
</main>
@endsection
