@php
$isHome      = request()->routeIs('home');
$isAbout     = request()->routeIs('about') || request()->routeIs('board') || request()->routeIs('leadership') || request()->routeIs('departments');
$isProjects  = request()->routeIs('projects') || request()->routeIs('project-detail');
$isResources = request()->routeIs('resources');
$isNews      = request()->routeIs('news');
$isContact   = request()->routeIs('contact');
@endphp

<div class="utility-bar">
  <div class="utility-row">
    <div class="utility-left">
      <span class="gog-badge"><span class="gh-flag"><span></span><span></span><span></span></span>An Agency of the Government of Ghana</span>
    </div>
    <div class="utility-right">
      <a class="utility-item util-btn" href="tel:0800600555"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/></svg><span class="util-label">Toll-Free 0800 600 555</span></a>
      <a class="utility-item util-btn" href="mailto:info@sifinghana.org"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="m4 6 8 7 8-7"/></svg><span class="util-label">Email Us</span></a>
      <span class="utility-item util-btn fs-controls" aria-label="Adjust text size">
        <button type="button" id="fsDec" aria-label="Decrease text size">A&minus;</button>
        <button type="button" id="fsReset" aria-label="Reset text size">A</button>
        <button type="button" id="fsInc" aria-label="Increase text size">A+</button>
      </span>
      <span class="utility-item util-btn">
        <select class="lang-select" aria-label="Select language">
          <option>English (EN)</option>
          <option disabled>Français — coming soon</option>
          <option disabled>Twi — coming soon</option>
        </select>
      </span>
    </div>
  </div>
</div>

<header class="site" id="site-header">
  <div class="nav-row">
    <a href="{{ route('home') }}" class="brand">
      <img src="https://sifinghana.org/backend/images/uploads/logo/sif-logo-new.png" alt="The Social Investment Fund logo" data-fallback>
      <span class="brand-text">
        <strong>The Social Investment Fund</strong>
        <span>Republic of Ghana</span>
      </span>
    </a>

    <nav class="primary" aria-label="Primary">
      <ul>
        <li class="nav-item {{ $isHome ? 'current' : '' }}"><a class="nav-link" href="{{ route('home') }}">Home</a></li>

        <li class="nav-item {{ $isAbout ? 'current' : '' }}">
          <button class="nav-link" aria-haspopup="true">About <svg viewBox="0 0 12 8" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M1 1l5 5 5-5"/></svg></button>
          <div class="mega mega-about">
            <div>
              <div class="mega-col-title">Institution</div>
              <a class="mega-link" href="{{ route('about') }}">About SIF</a>
              <a class="mega-link" href="{{ route('about') }}#mvv">Mission, Vision &amp; Values</a>
              <a class="mega-link" href="{{ route('about') }}#objectives">Strategic Objectives</a>
              <a class="mega-link" href="{{ route('about') }}#history">History &amp; Mandate</a>
            </div>
            <div>
              <div class="mega-col-title">Leadership &amp; Structure</div>
              <a class="mega-link" href="{{ route('board') }}">Board of Directors</a>
              <a class="mega-link" href="{{ route('leadership') }}#ceo">Chief Executive Officer</a>
              <a class="mega-link" href="{{ route('leadership') }}#management">Management Team</a>
              <a class="mega-link" href="{{ route('departments') }}#structure">Organisational Structure</a>
              <a class="mega-link" href="{{ route('departments') }}#departments">Departments &amp; Units</a>
              <a class="mega-link" href="{{ route('departments') }}#zones">Zonal Offices</a>
            </div>
          </div>
        </li>

        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#what-we-do">What We Do</a></li>

        <li class="nav-item {{ $isProjects ? 'current' : '' }}">
          <button class="nav-link" aria-haspopup="true">Projects <svg viewBox="0 0 12 8" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M1 1l5 5 5-5"/></svg></button>
          <div class="mega mega-projects">
            <div class="mega-projects-grid">
              <a class="mega-link" href="{{ route('projects') }}?status=ongoing">Active Projects</a>
              <a class="mega-link" href="{{ route('projects') }}?status=completed">Completed Projects</a>
              <a class="mega-link" href="{{ route('projects') }}#map">Project Map</a>
              <a class="mega-link" href="{{ route('project-detail', ['slug' => 'gwyesco']) }}">GWYESCO <span class="tag tag-new">New</span></a>
              <a class="mega-link" href="{{ route('project-detail', ['slug' => 'psdpep']) }}">PSDPEP <span class="tag tag-ongoing">Ongoing</span></a>
              <a class="mega-link" href="{{ route('project-detail', ['slug' => 'irdp2']) }}">IRDP II <span class="tag tag-ongoing">Ongoing</span></a>
              <a class="mega-link" href="{{ route('project-detail', ['slug' => 'irdp1']) }}">IRDP I</a>
              <a class="mega-link" href="{{ route('project-detail', ['slug' => 'uprp']) }}">UPRP</a>
              <a class="mega-link" href="{{ route('project-detail', ['slug' => 'gprp']) }}">GPRP I &amp; II</a>
            </div>
            <div class="mega-foot">
              <span style="font-size:12.5px;color:var(--ink-soft);">Six flagship programmes since 1998</span>
              <a href="{{ route('projects') }}">All Projects →</a>
            </div>
          </div>
        </li>

        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#impact">Impact</a></li>

        <li class="nav-item {{ $isResources ? 'current' : '' }}">
          <button class="nav-link" aria-haspopup="true">Resources <svg viewBox="0 0 12 8" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M1 1l5 5 5-5"/></svg></button>
          <div class="mega mega-resources">
            <div>
              <div class="mega-col-title">Documents</div>
              <a class="mega-link" href="{{ route('resources') }}#publications">Publications</a>
              <a class="mega-link" href="{{ route('resources') }}#reports">Annual Reports</a>
              <a class="mega-link" href="{{ route('resources') }}#procurement">Procurement Notices</a>
              <a class="mega-link" href="{{ route('resources') }}#esg">Environmental &amp; Social Documents</a>
            </div>
            <div>
              <div class="mega-col-title">More</div>
              <a class="mega-link" href="{{ route('resources') }}#policies">Policies</a>
              <a class="mega-link" href="{{ route('resources') }}">All Downloads</a>
              <a class="mega-link" href="{{ route('resources') }}#faq">Frequently Asked Questions</a>
            </div>
          </div>
        </li>

        <li class="nav-item {{ $isNews ? 'current' : '' }}"><a class="nav-link" href="{{ route('news') }}">News &amp; Media</a></li>
        <li class="nav-item {{ $isContact ? 'current' : '' }}"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
      </ul>
    </nav>

    <div class="nav-cta">
      <button class="search-trigger" data-search-trigger aria-label="Search the site">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
      </button>
      <a href="{{ route('complaint') }}" class="btn complaint-btn btn-sm">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 14 8 2l6 12H2Z"/><path d="M8 6.5v3.2M8 11.6h.01"/></svg>
        <span class="btn-text">Submit a Complaint</span>
      </a>
      <button class="burger" id="burgerBtn" aria-label="Open menu" aria-expanded="false">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      </button>
    </div>
  </div>
</header>

<!-- search overlay -->
<div class="search-overlay" id="searchOverlay">
  <div class="scrim"></div>
  <div class="search-panel">
    <div class="search-input-row">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
      <input type="text" id="searchInput" placeholder="Search projects, pages, documents…" autocomplete="off">
      <button type="button" id="searchClose">Esc</button>
    </div>
    <div class="search-results" id="searchResults"></div>
  </div>
</div>

<!-- mobile nav -->
<div class="mobile-nav" id="mobileNav">
  <div class="scrim"></div>
  <div class="mobile-nav-panel">
    <div class="mobile-nav-top">
      <span class="brand-text" style="font-family:var(--font-head);color:#fff;font-size:17px;font-weight:800;">Menu</span>
      <button class="mobile-nav-close" id="mobileNavClose" aria-label="Close menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 5l14 14M19 5L5 19"/></svg>
      </button>
    </div>
    <div class="mobile-nav-body">
      <a class="m-direct" href="{{ route('home') }}">Home</a>

      <div class="m-group">
        <button class="m-group-head">About <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v12M2 8h12"/></svg></button>
        <div class="m-sub">
          <a href="{{ route('about') }}">About SIF</a>
          <a href="{{ route('about') }}#mvv">Mission, Vision &amp; Values</a>
          <a href="{{ route('board') }}">Board of Directors</a>
          <a href="{{ route('leadership') }}#ceo">Chief Executive Officer</a>
          <a href="{{ route('leadership') }}#management">Management Team</a>
          <a href="{{ route('departments') }}#departments">Departments &amp; Units</a>
          <a href="{{ route('departments') }}#zones">Zonal Offices</a>
        </div>
      </div>

      <a class="m-direct" href="{{ route('home') }}#what-we-do">What We Do</a>

      <div class="m-group">
        <button class="m-group-head">Projects <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v12M2 8h12"/></svg></button>
        <div class="m-sub">
          <a href="{{ route('projects') }}">All Projects</a>
          <a href="{{ route('projects') }}#map">Project Map</a>
          <a href="{{ route('project-detail', ['slug' => 'gwyesco']) }}">GWYESCO</a>
          <a href="{{ route('project-detail', ['slug' => 'psdpep']) }}">PSDPEP</a>
          <a href="{{ route('project-detail', ['slug' => 'irdp2']) }}">IRDP II</a>
          <a href="{{ route('project-detail', ['slug' => 'irdp1']) }}">IRDP I</a>
          <a href="{{ route('project-detail', ['slug' => 'uprp']) }}">UPRP</a>
          <a href="{{ route('project-detail', ['slug' => 'gprp']) }}">GPRP I &amp; II</a>
        </div>
      </div>

      <a class="m-direct" href="{{ route('home') }}#impact">Impact</a>

      <div class="m-group">
        <button class="m-group-head">Resources <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v12M2 8h12"/></svg></button>
        <div class="m-sub">
          <a href="{{ route('resources') }}#publications">Publications</a>
          <a href="{{ route('resources') }}#reports">Annual Reports</a>
          <a href="{{ route('resources') }}#procurement">Procurement Notices</a>
          <a href="{{ route('resources') }}#esg">Environmental &amp; Social Documents</a>
          <a href="{{ route('resources') }}#faq">FAQ</a>
        </div>
      </div>

      <a class="m-direct" href="{{ route('news') }}">News &amp; Media</a>
      <a class="m-direct" href="{{ route('contact') }}">Contact</a>

      <div class="mobile-nav-foot">
        <a href="{{ route('complaint') }}" class="btn btn-red" style="justify-content:center;">Submit a Complaint</a>
        <a href="tel:0800600555" class="btn btn-outline on-dark" style="justify-content:center;">Call 0800 600 555</a>
      </div>
    </div>
  </div>
</div>
