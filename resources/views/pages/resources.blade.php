@extends('layouts.app')

@section('title', 'Resources | SIF Ghana')
@section('description', 'Download publications, annual reports, procurement notices, and environmental & social documents from SIF Ghana.')

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
      <a class="doc-card reveal" style="--i:0" href="https://sifinghana.org/images/DRAFT%20Social%20Investment%20Fund%20Corporate%20Profile.pdf" target="_blank" rel="noopener">
        <div class="doc-top"><div class="doc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 4h12l4 4v12H4z"/><path d="M16 4v4h4"/></svg></div><span class="doc-type">PDF</span></div>
        <h4>Corporate Profile</h4>
        <div class="doc-meta"><span>SIF Institutional</span><span>PDF</span></div>
        <span class="doc-action">Download <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v9m0 0 3.5-3.5M8 11 4.5 7.5M3 13.5h10"/></svg></span>
      </a>
      <a class="doc-card reveal" style="--i:1" href="https://sifinghana.org/publications.php" target="_blank" rel="noopener">
        <div class="doc-top"><div class="doc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 4h12l4 4v12H4z"/><path d="M8 9h8M8 13h8M8 17h5"/></svg></div><span class="doc-type">Archive</span></div>
        <h4>Publications Archive</h4>
        <div class="doc-meta"><span>SIF Ghana</span><span>Ongoing</span></div>
        <span class="doc-action">View Archive <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v9m0 0 3.5-3.5M8 11 4.5 7.5M3 13.5h10"/></svg></span>
      </a>
    </div>
  </div>
</section>

<!-- ANNUAL REPORTS -->
<section class="sec-tight bg-white" id="reports">
  <div class="container">
    <h3 style="font-size:19px;color:var(--forest);margin-bottom:18px;">Annual Reports</h3>
    <div class="doc-grid reveal-stagger">
      <a class="doc-card reveal" style="--i:0" href="https://sifinghana.org/annual-reports.php" target="_blank" rel="noopener">
        <div class="doc-top"><div class="doc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M5 3h14v18l-7-3-7 3Z"/></svg></div><span class="doc-type">Reports</span></div>
        <h4>SIF Annual Reports Archive</h4>
        <div class="doc-meta"><span>Financial &amp; Performance</span><span>Yearly</span></div>
        <span class="doc-action">View Archive <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v9m0 0 3.5-3.5M8 11 4.5 7.5M3 13.5h10"/></svg></span>
      </a>
    </div>
  </div>
</section>

<!-- PROCUREMENT -->
<section class="sec-tight bg-dim" id="procurement">
  <div class="container">
    <h3 style="font-size:19px;color:var(--forest);margin-bottom:18px;">Procurement Notices</h3>
    <div class="doc-grid reveal-stagger">
      <div class="doc-card reveal" style="--i:0">
        <div class="doc-top"><div class="doc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M9 3h6l-1 6h4l-9 12 2-9H7z"/></svg></div><span class="doc-type">Tenders</span></div>
        <h4>Current Tenders &amp; Contractor Opportunities</h4>
        <div class="doc-meta"><span>Updated periodically</span></div>
        <a href="/contact" class="doc-action">Contact Procurement <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v9m0 0 3.5-3.5M8 11 4.5 7.5M3 13.5h10"/></svg></a>
      </div>
    </div>
  </div>
</section>

<!-- ENVIRONMENTAL & SOCIAL -->
<section class="sec-tight bg-white" id="esg">
  <div class="container">
    <h3 style="font-size:19px;color:var(--forest);margin-bottom:18px;">Environmental &amp; Social Documents</h3>
    <div class="doc-grid reveal-stagger">
      <a class="doc-card reveal" style="--i:0" href="https://sifinghana.org/page.php?id=3253" target="_blank" rel="noopener">
        <div class="doc-top"><div class="doc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="9.5"/><path d="M12 7v5l3.5 2"/></svg></div><span class="doc-type">ESMP</span></div>
        <h4>Environmental &amp; Social Management Plan</h4>
        <div class="doc-meta"><span>GWYESCO</span><span>2025</span></div>
        <span class="doc-action">Read More <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v9m0 0 3.5-3.5M8 11 4.5 7.5M3 13.5h10"/></svg></span>
      </a>
    </div>
  </div>
</section>

<!-- POLICIES -->
<section class="sec-tight bg-dim" id="policies">
  <div class="container">
    <h3 style="font-size:19px;color:var(--forest);margin-bottom:18px;">Policies &amp; Downloads</h3>
    <div class="doc-grid reveal-stagger">
      <a class="doc-card reveal" style="--i:0" href="https://sifinghana.org/" target="_blank" rel="noopener">
        <div class="doc-top"><div class="doc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg></div><span class="doc-type">Policy</span></div>
        <h4>Institutional Policies</h4>
        <div class="doc-meta"><span>SIF Ghana</span></div>
        <span class="doc-action">Visit Main Site <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v9m0 0 3.5-3.5M8 11 4.5 7.5M3 13.5h10"/></svg></span>
      </a>
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
      <div class="accordion-item">
        <button class="accordion-head"><h4>Is SIF a government agency?</h4><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v12M2 8h12"/></svg></button>
        <div class="accordion-body"><p>Yes. SIF was incorporated in 1998 by the Government of Ghana together with the African Development Bank and the United Nations Development Programme, and operates as an independent, pro-poor development institution.</p></div>
      </div>
      <div class="accordion-item">
        <button class="accordion-head"><h4>How can my organisation partner with SIF?</h4><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v12M2 8h12"/></svg></button>
        <div class="accordion-body"><p>MMDAs, development partners, DFIs, the private sector and not-for-profit institutions can reach out via our <a href="/contact" style="color:var(--emerald);font-weight:700;">Contact page</a> to discuss potential collaboration.</p></div>
      </div>
      <div class="accordion-item">
        <button class="accordion-head"><h4>How do I submit a complaint or report a concern?</h4><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v12M2 8h12"/></svg></button>
        <div class="accordion-body"><p>Use the <a href="/complaint" style="color:var(--emerald);font-weight:700;">Submit a Complaint</a> portal for a guided 7-step process, call our toll-free line on 0800 600 555, or visit your nearest zonal office.</p></div>
      </div>
      <div class="accordion-item">
        <button class="accordion-head"><h4>Where are SIF&rsquo;s zonal offices located?</h4><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v12M2 8h12"/></svg></button>
        <div class="accordion-body"><p>SIF operates through four zones — Savannah Belt, Forest &amp; Transition, Western Coast and Eastern Seaboard — covering all sixteen regions. See the full list on our <a href="/departments#zones" style="color:var(--emerald);font-weight:700;">Departments &amp; Units page</a>.</p></div>
      </div>
      <div class="accordion-item">
        <button class="accordion-head"><h4>Can I apply for MSME microcredit through SIF?</h4><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v12M2 8h12"/></svg></button>
        <div class="accordion-body"><p>SIF&rsquo;s microcredit facilities, such as the US$4M facility under PSDPEP, are delivered in partnership with implementing institutions. Contact your nearest zonal office for current eligibility and application windows.</p></div>
      </div>
    </div>
  </div>
</section>

@endsection
