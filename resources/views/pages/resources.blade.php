@extends('layouts.app')

@section('title', 'Resources, Reports and Publications | SIF Ghana')
@section('description', 'Download Social Investment Fund Ghana annual reports, publications, procurement notices and environmental and social safeguard documents.')

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current">Resource Centre</span></div>
    <span class="eyebrow on-dark">Resource Centre</span>
    <h1>Reports, policies &amp; project documentation.</h1>
    <p>Search and filter SIF&rsquo;s public documents — corporate reporting, project safeguards, policies and procurement notices.</p>
  </div>
</section>

<section class="sec bg-white">
  <div class="container">
    <div class="toolbar reveal">
      <div class="search-bar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" placeholder="Search documents…" aria-label="Search documents">
      </div>
      <select class="select-pill" aria-label="Filter by year"><option>All Years</option><option>2026</option><option>2025</option></select>
      <select class="select-pill" aria-label="Filter by programme"><option>All Programmes</option><option>GWYESCO</option><option>PSDPEP</option><option>IRDP II</option></select>
    </div>
    <div class="filter-row reveal">
      <span class="chip is-active">All Documents</span>
      <span class="chip">Reports</span>
      <span class="chip">Publications</span>
      <span class="chip">Procurement</span>
      <span class="chip">Environmental &amp; Social</span>
      <span class="chip">Policies</span>
    </div>
  </div>
</section>

<!-- PUBLICATIONS -->
<section class="sec-tight bg-dim" id="publications">
  <div class="container">
    <h3 style="font-size:19px;color:var(--forest);margin-bottom:18px;">Publications</h3>
    <div class="doc-grid reveal-stagger">
      @forelse(($documentsByType['publications'] ?? collect()) as $index => $document)
        @include('partials.cms-doc-card', ['document' => $document, 'index' => $index])
      @empty
        <div class="doc-card reveal" style="--i:0"><h4>No publications yet</h4><div class="doc-meta"><span>Publish documents in the CMS.</span></div></div>
      @endforelse
    </div>
  </div>
</section>

<!-- ANNUAL REPORTS -->
<section class="sec-tight bg-white" id="reports">
  <div class="container">
    <h3 style="font-size:19px;color:var(--forest);margin-bottom:18px;">Annual Reports</h3>
    <div class="doc-grid reveal-stagger">
      @forelse(($documentsByType['annual-reports'] ?? collect()) as $index => $document)
        @include('partials.cms-doc-card', ['document' => $document, 'index' => $index])
      @empty
        <div class="doc-card reveal" style="--i:0"><h4>No annual reports yet</h4><div class="doc-meta"><span>Publish documents in the CMS.</span></div></div>
      @endforelse
    </div>
  </div>
</section>

<!-- PROCUREMENT -->
<section class="sec-tight bg-dim" id="procurement">
  <div class="container">
    <h3 style="font-size:19px;color:var(--forest);margin-bottom:18px;">Procurement Notices</h3>
    <div class="doc-grid reveal-stagger">
      @forelse(($documentsByType['procurement-notices'] ?? collect()) as $index => $document)
        @include('partials.cms-doc-card', ['document' => $document, 'index' => $index])
      @empty
        <div class="doc-card reveal" style="--i:0"><h4>Current Tenders &amp; Contractor Opportunities</h4><div class="doc-meta"><span>Updated periodically</span></div><a href="/contact" class="doc-action">Contact Procurement <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v9m0 0 3.5-3.5M8 11 4.5 7.5M3 13.5h10"/></svg></a></div>
      @endforelse
    </div>
  </div>
</section>

<!-- ENVIRONMENTAL & SOCIAL -->
<section class="sec-tight bg-white" id="esg">
  <div class="container">
    <h3 style="font-size:19px;color:var(--forest);margin-bottom:18px;">Environmental &amp; Social Documents</h3>
    <div class="doc-grid reveal-stagger">
      @forelse(($documentsByType['environmental-social-documents'] ?? collect()) as $index => $document)
        @include('partials.cms-doc-card', ['document' => $document, 'index' => $index])
      @empty
        <div class="doc-card reveal" style="--i:0"><h4>No environmental documents yet</h4><div class="doc-meta"><span>Publish documents in the CMS.</span></div></div>
      @endforelse
    </div>
  </div>
</section>

<!-- POLICIES -->
<section class="sec-tight bg-dim" id="policies">
  <div class="container">
    <h3 style="font-size:19px;color:var(--forest);margin-bottom:18px;">Policies &amp; Downloads</h3>
    <div class="doc-grid reveal-stagger">
      @forelse(($documentsByType['policies-downloads'] ?? collect()) as $index => $document)
        @include('partials.cms-doc-card', ['document' => $document, 'index' => $index])
      @empty
        <div class="doc-card reveal" style="--i:0"><h4>No policies yet</h4><div class="doc-meta"><span>Publish documents in the CMS.</span></div></div>
      @endforelse
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="sec bg-white" id="faq">
  <div class="container-narrow">
    <div class="section-head reveal center">
      <span class="eyebrow">Frequently Asked Questions</span>
      <h2>Common questions about SIF&rsquo;s work.</h2>
    </div>
    <div class="reveal">
      @foreach(($faqs ?? \App\Models\Faq::fallbackFrontendFaqs()) as $faq)
        <div class="accordion-item">
          <button class="accordion-head"><h4>{{ $faq['question'] }}</h4><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v12M2 8h12"/></svg></button>
          <div class="accordion-body"><p>{{ $faq['answer'] }}</p></div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@endsection
