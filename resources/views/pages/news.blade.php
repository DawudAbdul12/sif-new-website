@extends('layouts.app')

@section('title', 'News and Media | SIF Ghana')
@section('description', 'Read the latest news, press releases, announcements and media updates from the Social Investment Fund Ghana.')

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current">News &amp; Media</span></div>
    <span class="eyebrow on-dark">News &amp; Media Centre</span>
    <h1>From the field.</h1>
    <p>Programme updates, partnership news and announcements from across SIF&rsquo;s portfolio.</p>
  </div>
</section>

<section class="sec bg-white">
  <div class="container">
    <div class="toolbar reveal">
      <div class="search-bar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" placeholder="Search news and announcements…" aria-label="Search news">
      </div>
      <select class="select-pill" aria-label="Filter by year">
        <option>All Years</option>
        <option>2026</option>
        <option>2025</option>
      </select>
    </div>

    <div class="filter-row reveal">
      <span class="chip is-active">All</span>
      @foreach(($newsCategories ?? collect(['Programme Updates', 'Partnerships', 'Procurement', 'Community', 'Environmental & Social'])) as $category)
        <span class="chip">{{ $category }}</span>
      @endforeach
    </div>

    <div class="news-grid reveal-stagger">
      @forelse(($newsPosts ?? collect()) as $index => $post)
        @include('partials.cms-news-card', ['post' => $post, 'index' => $index, 'featured' => $loop->first])
      @empty
        <div class="announce-strip reveal">
          <span class="announce-pill"><span><strong>No published news yet</strong><span>Publish posts in the CMS and they will appear here.</span></span></span>
        </div>
      @endforelse
    </div>
    @if(($newsPosts ?? null) instanceof \Illuminate\Contracts\Pagination\Paginator)
      <div class="mt-4">
        @include('partials.compact-pagination', ['paginator' => $newsPosts])
      </div>
    @endif

    <div class="announce-strip reveal">
      <a class="announce-pill" href="/resources#procurement"><span class="aicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M9 3h6l-1 6h4l-9 12 2-9H7z"/></svg></span><span><strong>Procurement Notices</strong><span>Open tenders &amp; contractor opportunities</span></span></a>
      <a class="announce-pill" href="/resources#esg"><span class="aicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="9.5"/><path d="M12 7v5l3.5 2"/></svg></span><span><strong>Public Consultations</strong><span>Community engagement schedules</span></span></a>
      <a class="announce-pill" href="/resources#esg"><span class="aicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 21s-7-4.6-7-10.6A5.4 5.4 0 0 1 12 6a5.4 5.4 0 0 1 7 4.4C19 16.4 12 21 12 21Z"/></svg></span><span><strong>Environmental Documents</strong><span>ESMPs &amp; safeguard reporting</span></span></a>
      <a class="announce-pill" href="/contact"><span class="aicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 21h18M5 21V7l7-4 7 4v14"/></svg></span><span><strong>Vacancies</strong><span>Current opportunities at SIF</span></span></a>
    </div>
  </div>
</section>

<!-- PRESS, SPEECHES, GALLERY -->
<section class="sec bg-dim">
  <div class="container">
    <div class="dept-grid reveal-stagger" style="grid-template-columns:repeat(2,1fr);">
      <div class="dept-cell reveal" style="--i:0">
        <span class="dnum">Archive</span>
        <h4>Press Releases &amp; Speeches</h4>
        <p>SIF&rsquo;s full archive of press releases and official speeches is published on the main SIF news portal.</p>
        <a href="https://sifinghana.org/" target="_blank" rel="noopener" class="btn-ghost" style="margin-top:10px;">Visit News Portal <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></a>
      </div>
      <div class="dept-cell reveal" style="--i:1">
        <span class="dnum">Media</span>
        <h4>Photo &amp; Video Gallery</h4>
        <p>Browse photography from project sites, handovers and missions across all four operational zones.</p>
        <a href="https://sifinghana.org/gallery.php" target="_blank" rel="noopener" class="btn-ghost" style="margin-top:10px;">View Gallery <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></a>
      </div>
    </div>

    <div class="reveal" style="margin-top:36px;background:#fff;border:1px solid var(--line);border-radius:var(--radius-m);padding:26px 28px;display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap;">
      <div>
        <h4 style="font-size:15.5px;color:var(--forest);">Media Contact</h4>
        <p style="font-size:13.5px;color:var(--ink-soft);margin-top:4px;">For press enquiries, interview requests or media accreditation.</p>
      </div>
      <div style="display:flex;gap:14px;flex-wrap:wrap;">
        <a href="mailto:info@sifinghana.org" class="btn btn-outline btn-sm">info@sifinghana.org</a>
        <a href="tel:+233302778920" class="btn btn-outline btn-sm">+233 (0)302 778 920</a>
      </div>
    </div>
  </div>
</section>

@endsection
