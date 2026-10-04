@extends('layouts.app')

@section('title', 'Notices - Social Investment Fund')

@section('content')
<main class="page-main">
  <section class="page-hero">
    <div class="container"><span class="eyebrow">Public Notices</span><h1>Notices</h1></div>
  </section>

  <section class="sec bg-white">
    <div class="container">
      <div class="doc-grid">
        @forelse($notices as $notice)
          <article class="doc-card reveal">
            <div class="doc-top"><span class="doc-type">{{ $notice->published_at?->format('F j, Y') }}</span></div>
            <h4>{{ $notice->title }}</h4>
            @if($notice->brief_description)<p>{{ $notice->brief_description }}</p>@endif
            @if($notice->body)<div>{!! $notice->body !!}</div>@endif
            @if($notice->fileUrl())<a class="doc-action" href="{{ $notice->fileUrl() }}" target="_blank" rel="noopener">View or Download</a>@endif
          </article>
        @empty
          <p>No notices have been published yet.</p>
        @endforelse
      </div>
      @include('partials.cms-pagination-summary', ['paginator' => $notices])
    </div>
  </section>
</main>
@endsection
