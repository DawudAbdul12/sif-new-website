@extends('layouts.app')

@section('title', 'Sitemap | SIF Ghana')
@section('description', 'Full sitemap of the Social Investment Fund Ghana website.')

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current">Sitemap</span></div>
    <span class="eyebrow on-dark">Sitemap</span>
    <h1>Every page, in one place.</h1>
  </div>
</section>

<section class="sec bg-white">
  <div class="container">
    <div class="dept-grid reveal-stagger" style="grid-template-columns:repeat(3,1fr);">
      <div class="dept-cell reveal" style="--i:0">
        <h4>Institution</h4>
        <ul style="display:flex;flex-direction:column;gap:9px;margin-top:10px;">
          <li><a href="/" style="color:var(--ink-soft);">Home</a></li>
          <li><a href="/about" style="color:var(--ink-soft);">About SIF</a></li>
          <li><a href="/about#mvv" style="color:var(--ink-soft);">Mission, Vision &amp; Values</a></li>
          <li><a href="/about#objectives" style="color:var(--ink-soft);">Strategic Objectives</a></li>
          <li><a href="/leadership" style="color:var(--ink-soft);">Leadership</a></li>
          <li><a href="/departments" style="color:var(--ink-soft);">Departments &amp; Units</a></li>
          <li><a href="/departments#zones" style="color:var(--ink-soft);">Zonal Offices</a></li>
        </ul>
      </div>
      <div class="dept-cell reveal" style="--i:1">
        <h4>Projects &amp; Impact</h4>
        <ul style="display:flex;flex-direction:column;gap:9px;margin-top:10px;">
          <li><a href="/#what-we-do" style="color:var(--ink-soft);">What We Do</a></li>
          <li><a href="/#impact" style="color:var(--ink-soft);">Our Impact</a></li>
          <li><a href="/projects" style="color:var(--ink-soft);">Projects Directory</a></li>
          <li><a href="/projects#map" style="color:var(--ink-soft);">Project Map</a></li>
          <li><a href="/projects/gwyesco" style="color:var(--ink-soft);">GWYESCO</a></li>
          <li><a href="/projects/psdpep" style="color:var(--ink-soft);">PSDPEP</a></li>
          <li><a href="/projects/irdp2" style="color:var(--ink-soft);">IRDP II</a></li>
          <li><a href="/projects/irdp1" style="color:var(--ink-soft);">IRDP I</a></li>
          <li><a href="/projects/uprp" style="color:var(--ink-soft);">UPRP</a></li>
          <li><a href="/projects/gprp" style="color:var(--ink-soft);">GPRP I &amp; II</a></li>
        </ul>
      </div>
      <div class="dept-cell reveal" style="--i:2">
        <h4>Resources &amp; Contact</h4>
        <ul style="display:flex;flex-direction:column;gap:9px;margin-top:10px;">
          <li><a href="/news" style="color:var(--ink-soft);">News &amp; Media</a></li>
          <li><a href="/resources" style="color:var(--ink-soft);">Resource Centre</a></li>
          <li><a href="/resources#faq" style="color:var(--ink-soft);">FAQ</a></li>
          <li><a href="/complaint" style="color:var(--ink-soft);">Submit a Complaint</a></li>
          <li><a href="/contact" style="color:var(--ink-soft);">Contact Us</a></li>
          <li><a href="/privacy" style="color:var(--ink-soft);">Privacy Policy</a></li>
          <li><a href="/accessibility" style="color:var(--ink-soft);">Accessibility Statement</a></li>
          <li><a href="/terms" style="color:var(--ink-soft);">Terms of Use</a></li>
        </ul>
      </div>
    </div>
  </div>
</section>

@endsection
