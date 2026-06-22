@extends('layouts.app')

@section('title', 'Accessibility Statement | SIF Ghana')
@section('description', 'SIF Ghana Accessibility Statement — our commitment to inclusive and accessible web content.')

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current">Accessibility</span></div>
    <span class="eyebrow on-dark">Legal</span>
    <h1>Accessibility Statement</h1>
    <p>Our commitment to making this site usable for everyone, regardless of ability.</p>
  </div>
</section>

<section class="sec bg-white">
  <div class="container-narrow" style="display:flex;flex-direction:column;gap:28px;">
    <div class="reveal">
      <h3 style="font-size:18px;color:var(--forest);margin-bottom:10px;">Our Commitment</h3>
      <p style="color:var(--ink-soft);font-size:14.5px;line-height:1.7;">This site is designed to meet WCAG 2.2 Level AA accessibility guidelines. We continually test and improve accessibility as part of regular site maintenance.</p>
    </div>
    <div class="reveal">
      <h3 style="font-size:18px;color:var(--forest);margin-bottom:10px;">Features Built Into This Site</h3>
      <ul style="display:flex;flex-direction:column;gap:10px;margin-top:6px;">
        <li style="color:var(--ink-soft);font-size:14.5px;line-height:1.6;">A "Skip to main content" link for keyboard and screen-reader users.</li>
        <li style="color:var(--ink-soft);font-size:14.5px;line-height:1.6;">Adjustable text size (A− / A / A+) in the top utility bar.</li>
        <li style="color:var(--ink-soft);font-size:14.5px;line-height:1.6;">Visible focus outlines on every interactive element.</li>
        <li style="color:var(--ink-soft);font-size:14.5px;line-height:1.6;">Reduced-motion support — animations are minimised if your system requests it.</li>
        <li style="color:var(--ink-soft);font-size:14.5px;line-height:1.6;">High-contrast colour combinations across text and backgrounds.</li>
        <li style="color:var(--ink-soft);font-size:14.5px;line-height:1.6;">Semantic HTML structure and descriptive alt text for meaningful images.</li>
        <li style="color:var(--ink-soft);font-size:14.5px;line-height:1.6;">Large touch targets and keyboard-operable menus, forms and the complaint stepper.</li>
      </ul>
    </div>
    <div class="reveal">
      <h3 style="font-size:18px;color:var(--forest);margin-bottom:10px;">Feedback</h3>
      <p style="color:var(--ink-soft);font-size:14.5px;line-height:1.7;">If you encounter an accessibility barrier anywhere on this site, please let us know via <a href="mailto:info@sifinghana.org" style="color:var(--emerald);font-weight:700;">info@sifinghana.org</a> or our toll-free line on 0800 600 555, so we can address it.</p>
    </div>
  </div>
</section>

@endsection
