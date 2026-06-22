@extends('layouts.app')

@section('title', 'Privacy Policy | SIF Ghana')
@section('description', 'Privacy Policy for the Social Investment Fund Ghana website.')

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current">Privacy Policy</span></div>
    <span class="eyebrow on-dark">Legal</span>
    <h1>Privacy Policy</h1>
    <p>How we handle information shared with the Social Investment Fund through this website.</p>
  </div>
</section>

<section class="sec bg-white">
  <div class="container-narrow" style="display:flex;flex-direction:column;gap:28px;">
    <div class="reveal">
      <h3 style="font-size:18px;color:var(--forest);margin-bottom:10px;">Information We Collect</h3>
      <p style="color:var(--ink-soft);font-size:14.5px;line-height:1.7;">When you submit a complaint, enquiry or newsletter sign-up through this site, we collect only the information you choose to provide — such as your name, contact details and the content of your message. Anonymous submissions are accepted wherever the form allows it.</p>
    </div>
    <div class="reveal">
      <h3 style="font-size:18px;color:var(--forest);margin-bottom:10px;">How We Use It</h3>
      <p style="color:var(--ink-soft);font-size:14.5px;line-height:1.7;">Information submitted is used solely to respond to your enquiry or complaint, to improve our programmes, and to send newsletter updates if you have opted in. We do not sell personal data to third parties.</p>
    </div>
    <div class="reveal">
      <h3 style="font-size:18px;color:var(--forest);margin-bottom:10px;">Cookies</h3>
      <p style="color:var(--ink-soft);font-size:14.5px;line-height:1.7;">This site uses essential cookies to keep the site secure and to remember accessibility preferences such as text size. We do not use cookies for third-party advertising.</p>
    </div>
    <div class="reveal">
      <h3 style="font-size:18px;color:var(--forest);margin-bottom:10px;">Your Rights</h3>
      <p style="color:var(--ink-soft);font-size:14.5px;line-height:1.7;">You may request access to, correction of, or deletion of any personal information you have submitted by contacting us at <a href="mailto:info@sifinghana.org" style="color:var(--emerald);font-weight:700;">info@sifinghana.org</a> or by calling our toll-free line on 0800 600 555.</p>
    </div>
    <div class="reveal">
      <h3 style="font-size:18px;color:var(--forest);margin-bottom:10px;">Contact</h3>
      <p style="color:var(--ink-soft);font-size:14.5px;line-height:1.7;">Questions about this policy can be directed to SIF's Fund Management Unit, Off El-Wak Stadium Road, Accra, or via the <a href="/contact" style="color:var(--emerald);font-weight:700;">Contact page</a>.</p>
    </div>
  </div>
</section>

@endsection
