@extends('layouts.app')

@section('title', 'Terms of Use | SIF Ghana')
@section('description', 'Terms of use governing access to the Social Investment Fund Ghana website.')

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current">Terms of Use</span></div>
    <span class="eyebrow on-dark">Legal</span>
    <h1>Terms of Use</h1>
    <p>The terms governing use of this website.</p>
  </div>
</section>

<section class="sec bg-white">
  <div class="container-narrow" style="display:flex;flex-direction:column;gap:28px;">
    <div class="reveal">
      <h3 style="font-size:18px;color:var(--forest);margin-bottom:10px;">Acceptance of Terms</h3>
      <p style="color:var(--ink-soft);font-size:14.5px;line-height:1.7;">By accessing this website, you agree to use it responsibly and in accordance with these terms. This site is maintained by the Social Investment Fund (SIF), Ghana.</p>
    </div>
    <div class="reveal">
      <h3 style="font-size:18px;color:var(--forest);margin-bottom:10px;">Content Accuracy</h3>
      <p style="color:var(--ink-soft);font-size:14.5px;line-height:1.7;">We make reasonable efforts to keep information on this site accurate and current. Project figures, statistics and document links are reviewed periodically. For the most current official figures, please refer to SIF's published Annual Reports.</p>
    </div>
    <div class="reveal">
      <h3 style="font-size:18px;color:var(--forest);margin-bottom:10px;">Intellectual Property</h3>
      <p style="color:var(--ink-soft);font-size:14.5px;line-height:1.7;">All logos, project imagery and institutional content on this site belong to SIF or its respective partners and may not be reproduced for commercial purposes without permission.</p>
    </div>
    <div class="reveal">
      <h3 style="font-size:18px;color:var(--forest);margin-bottom:10px;">External Links</h3>
      <p style="color:var(--ink-soft);font-size:14.5px;line-height:1.7;">This site links to external resources, including partner and news websites. SIF is not responsible for the content or privacy practices of external sites.</p>
    </div>
    <div class="reveal">
      <h3 style="font-size:18px;color:var(--forest);margin-bottom:10px;">Contact</h3>
      <p style="color:var(--ink-soft);font-size:14.5px;line-height:1.7;">Questions about these terms can be directed to <a href="mailto:info@sifinghana.org" style="color:var(--emerald);font-weight:700;">info@sifinghana.org</a>.</p>
    </div>
  </div>
</section>

@endsection
