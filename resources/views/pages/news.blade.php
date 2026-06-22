@extends('layouts.app')

@section('title', 'News & Media | SIF Ghana')
@section('description', 'Latest news, press releases, and media coverage from the Social Investment Fund Ghana.')

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
      <span class="chip">Programme Updates</span>
      <span class="chip">Partnerships</span>
      <span class="chip">Procurement</span>
      <span class="chip">Community</span>
      <span class="chip">Environmental &amp; Social</span>
    </div>

    <div class="news-grid reveal-stagger">
      <a class="news-card news-feature reveal" style="--i:0" href="https://www.myjoyonline.com/ghana-launches-landmark-women-and-youth-employment-programme-to-create-over-30000-jobs/" target="_blank" rel="noopener">
        <div class="nimg"><span class="ncat">Programme Update</span><img src="/images/gwyesco-launch.jpg" alt="" data-fallback loading="lazy"></div>
        <div class="nbody">
          <span class="ndate">June 2026</span>
          <h3>Ghana launches GWYESCO to create 30,000+ jobs for women and youth</h3>
          <p>A US$71.25M AfDB grant backs Ghana&rsquo;s first results-based financing programme, implemented by SIF in partnership with the Ministry of Finance.</p>
          <span class="nmore">Read the story <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></span>
        </div>
      </a>
      <a class="news-card reveal" style="--i:1" href="https://sifinghana.org/page.php?id=3278" target="_blank" rel="noopener">
        <div class="nimg"><span class="ncat">Partnerships</span><img src="https://sifinghana.org/backend/images/uploads/20260119_133435-2.jpg.jpeg1769166677.jpeg" alt="" data-fallback loading="lazy"></div>
        <div class="nbody"><span class="ndate">January 2026</span><h3>BADEA appraisal mission meets with SIF &amp; Ministry of Finance</h3><p>A BADEA delegation visited SIF and the Ministry of Finance as part of an ongoing appraisal mission.</p><span class="nmore">Read more <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></span></div>
      </a>
      <a class="news-card reveal" style="--i:2" href="https://sifinghana.org/page.php?id=3255" target="_blank" rel="noopener">
        <div class="nimg"><span class="ncat">Community</span><img src="https://sifinghana.org/backend/images/uploads/WhatsApp%20Image%202026-01-21%20at%204.15.48%20PM.jpeg1769013143.jpeg" alt="" data-fallback loading="lazy"></div>
        <div class="nbody"><span class="ndate">January 2026</span><h3>SIF hands over UG Biotechnology Centre site to contractors</h3><p>Site handover marks the start of construction works at the University of Ghana Biotechnology Centre.</p><span class="nmore">Read more <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></span></div>
      </a>
      <a class="news-card reveal" style="--i:3" href="https://sifinghana.org/page.php?id=3253" target="_blank" rel="noopener">
        <div class="nimg"><span class="ncat">Environmental &amp; Social</span><img src="https://sifinghana.org/backend/images/uploads/1751046892_8d6c9a3176e1e094_SIFNEWPROJECTESMP.jpg" alt="" data-fallback loading="lazy"></div>
        <div class="nbody"><span class="ndate">2025</span><h3>Environmental &amp; Social Management Plan published</h3><p>SIF publishes the ESMP covering safeguard arrangements for its newest programme.</p><span class="nmore">Read more <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></span></div>
      </a>
    </div>

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
