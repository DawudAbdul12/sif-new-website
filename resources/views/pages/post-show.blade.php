@php
  $categoryLabel = $post->categoryRelation?->name ?? $fallbackLabel;
  $publishedDate = $post->published_at?->format('F j, Y');
  $plainExcerpt = trim(strip_tags((string) $post->excerpt));
  $description = $post->seo_description ?: $plainExcerpt;
@endphp

@extends('layouts.app')

@section('title', $post->seo_title ?: $post->title.' | SIF Ghana')
@section('description', $description ?: 'Read updates from the Social Investment Fund Ghana.')
@section('seo_type', 'article')
@section('seo_image', $post->imageUrl())

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><a href="{{ route('news') }}">News &amp; Media</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current">{{ $categoryLabel }}</span></div>
    <span class="eyebrow on-dark">{{ $categoryLabel }}</span>
    <h1>{{ $post->title }}</h1>
    @if($publishedDate)<p>{{ $publishedDate }}</p>@endif
  </div>
</section>

<section class="sec bg-white">
  <div class="container-narrow">
    <article class="reveal">
      <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" style="width:100%;max-height:460px;object-fit:cover;border-radius:var(--radius-m);margin-bottom:28px;" data-fallback>
      <div class="cms-body" style="color:var(--ink-soft);font-size:15.5px;line-height:1.8;">
        {!! $post->body ?: '<p>'.e($plainExcerpt).'</p>' !!}
      </div>
    </article>
  </div>
</section>

@if($relatedPosts->isNotEmpty())
<section class="sec bg-dim">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">{{ $relatedTitle }}</span>
      <h2>More from SIF Ghana.</h2>
    </div>
    <div class="news-grid reveal-stagger">
      @foreach($relatedPosts as $index => $relatedPost)
        @include('partials.cms-news-card', ['post' => $relatedPost, 'index' => $index])
      @endforeach
    </div>
  </div>
</section>
@endif
@endsection
