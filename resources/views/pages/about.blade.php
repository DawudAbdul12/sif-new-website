@extends('layouts.app')

@section('title', 'About SIF | The Social Investment Fund Ghana')
@section('description', 'Learn about the Social Investment Fund Ghana — our mandate, governance, mission, values, history and national development impact since 1998.')

@push('head')
<style>
.about-hero{padding:42px 0 0;}
.about-hero-grid{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(360px,.95fr);gap:48px;align-items:end;position:relative;z-index:1;}
.about-hero-copy{padding:10px 0 54px;max-width:720px;}
.about-hero-copy h1{color:#fff;font-size:clamp(36px,5vw,64px);line-height:1.02;margin:18px 0 18px;max-width:760px;}
.about-hero-copy p{color:rgba(248,246,240,.8);font-size:17px;line-height:1.75;max-width:640px;}
.about-hero-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:28px;}
.about-hero-card{background:rgba(255,255,255,.08);border:1px solid var(--line-dark);border-radius:var(--radius-l) var(--radius-l) 0 0;padding:18px;box-shadow:0 -20px 60px rgba(0,0,0,.14);backdrop-filter:blur(10px);}
.about-hero-img{position:relative;overflow:hidden;border-radius:16px;aspect-ratio:4/3;background:rgba(255,255,255,.08);}
.about-hero-img img{width:100%;height:100%;object-fit:cover;}
.about-hero-img::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,transparent 45%,rgba(7,59,42,.82));}
.about-hero-caption{position:absolute;left:18px;right:18px;bottom:16px;color:#fff;z-index:1;font-size:13px;line-height:1.45;}
.about-hero-caption strong{display:block;font-family:var(--font-head);font-size:15px;margin-bottom:2px;}
.about-stat-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--line);border-radius:var(--radius-l);overflow:hidden;box-shadow:var(--shadow-m);transform:translateY(50%);}
.about-stat{background:#fff;padding:24px 22px;}
.about-stat strong{display:block;font-family:var(--font-head);font-size:clamp(26px,3vw,36px);line-height:1;color:var(--forest);}
.about-stat span{display:block;color:var(--ink-soft);font-size:12.5px;line-height:1.45;margin-top:8px;}
.about-overview{padding-top:128px;}
.about-editorial{display:grid;grid-template-columns:minmax(0,1fr) 380px;gap:64px;align-items:start;}
.about-kicker-line{width:54px;height:3px;background:var(--gold);border-radius:3px;margin:22px 0;}
.about-copy h2{font-size:clamp(30px,4vw,48px);line-height:1.08;margin-top:14px;max-width:760px;}
.about-copy .lead{font-family:var(--font-head);font-size:20px;line-height:1.55;color:var(--forest);font-weight:650;margin:22px 0 18px;}
.about-copy p:not(.lead){color:var(--ink-soft);font-size:15.5px;line-height:1.8;margin-top:16px;}
.about-side-panel{position:sticky;top:112px;background:linear-gradient(rgba(7,59,42,0.78),rgba(7,59,42,0.78)),var(--sif-pattern) center/cover no-repeat,var(--forest);color:#fff;border-radius:var(--radius-l);padding:28px;box-shadow:var(--shadow-l);}
.about-side-panel h3{color:#fff;font-size:20px;margin:12px 0 10px;}
.about-side-panel p{color:rgba(248,246,240,.72);font-size:14px;line-height:1.65;}
.about-proof-list{display:grid;gap:12px;margin-top:22px;}
.about-proof{display:flex;gap:12px;align-items:flex-start;padding-top:12px;border-top:1px solid var(--line-dark);}
.about-proof svg{width:18px;height:18px;color:var(--gold);flex:none;margin-top:2px;}
.about-proof span{font-size:13.5px;color:rgba(248,246,240,.82);line-height:1.5;}
.mandate-feature{display:grid;grid-template-columns:.9fr 1.1fr;border-radius:var(--radius-l);overflow:hidden;background:#fff;border:1px solid var(--line);box-shadow:var(--shadow-s);}
.mandate-media{min-height:360px;position:relative;background:var(--cream-dim);}
.mandate-media img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;}
.mandate-media::after{content:"";position:absolute;inset:0;background:linear-gradient(135deg,rgba(7,59,42,.25),transparent);}
.mandate-body{padding:48px;display:flex;flex-direction:column;justify-content:center;}
.mandate-body h2{font-size:clamp(26px,3vw,38px);line-height:1.12;margin:14px 0 16px;}
.mandate-body p{color:var(--ink-soft);font-size:15.5px;line-height:1.8;}
.mandate-pills{display:flex;gap:8px;flex-wrap:wrap;margin-top:24px;}
.mandate-pills span{background:var(--emerald-light);color:var(--emerald);font-size:12px;font-weight:700;border-radius:var(--radius-full);padding:7px 12px;}
.history-grid{display:grid;grid-template-columns:340px minmax(0,1fr);gap:54px;align-items:start;}
.history-rail{display:grid;gap:14px;}
.history-card{background:#fff;border:1px solid var(--line);border-radius:var(--radius-m);padding:20px 22px;position:relative;overflow:hidden;}
.history-card::before{content:"";position:absolute;left:0;top:0;bottom:0;width:4px;background:var(--gold);}
.history-card .year{font-family:var(--font-head);font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:var(--emerald);}
.history-card h3{font-size:17px;margin:8px 0 6px;}
.history-card p{font-size:13.5px;color:var(--ink-soft);line-height:1.65;}
.mv-premium-grid{display:grid;grid-template-columns:1fr 1fr;gap:22px;margin-bottom:30px;}
.mv-premium{background:#fff;border:1px solid var(--line);border-radius:var(--radius-l);padding:34px;box-shadow:var(--shadow-s);position:relative;overflow:hidden;}
.mv-premium::after{content:"";position:absolute;right:-56px;top:-56px;width:150px;height:150px;border-radius:50%;background:var(--gold-soft);}
.mv-premium > *{position:relative;z-index:1;}
.mv-premium h3{font-size:22px;line-height:1.2;margin:14px 0 12px;}
.mv-premium p{color:var(--ink-soft);font-size:15px;line-height:1.75;}
.values-premium-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;}
.value-premium{background:#fff;border:1px solid var(--line);border-radius:var(--radius-m);padding:22px;min-height:210px;display:flex;flex-direction:column;transition:transform .2s var(--ease),box-shadow .2s var(--ease),border-color .2s;}
.value-premium:hover{transform:translateY(-4px);box-shadow:var(--shadow-m);border-color:transparent;}
.value-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;}
.value-num{font-family:var(--font-head);font-size:12px;font-weight:800;color:var(--ink-soft);}
.value-icon{width:40px;height:40px;border-radius:12px;background:var(--emerald-light);color:var(--emerald);display:flex;align-items:center;justify-content:center;}
.value-icon svg{width:19px;height:19px;}
.value-premium h4{font-size:16px;line-height:1.25;margin-bottom:8px;}
.value-premium p{color:var(--ink-soft);font-size:13.5px;line-height:1.6;}
.value-download{background:linear-gradient(rgba(7,59,42,0.78),rgba(7,59,42,0.78)),var(--sif-pattern) center/cover no-repeat,var(--forest);border-color:var(--forest);}
.value-download h4{color:#fff;}
.value-download p{color:rgba(248,246,240,.68);}
.value-download .value-icon{background:rgba(214,167,44,.14);color:var(--gold-light);}
.value-download .value-num{color:rgba(248,246,240,.55);}
.value-download a{margin-top:auto;color:var(--gold-light);font-size:13px;font-weight:800;display:inline-flex;align-items:center;gap:7px;}
.objectives-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;}
.objective-card{background:#fff;border:1px solid var(--line);border-radius:var(--radius-m);padding:24px;position:relative;overflow:hidden;}
.objective-card::before{content:attr(data-num);position:absolute;right:18px;top:12px;font-family:var(--font-head);font-size:42px;font-weight:800;color:rgba(7,59,42,.06);line-height:1;}
.objective-card h4{font-size:16px;line-height:1.3;margin-bottom:10px;position:relative;}
.objective-card p{color:var(--ink-soft);font-size:13.5px;line-height:1.62;position:relative;}
.about-cta{background:linear-gradient(rgba(7,59,42,0.78),rgba(7,59,42,0.78)),var(--sif-pattern) center/cover no-repeat,var(--forest);border-radius:var(--radius-l);padding:44px 48px;display:flex;align-items:center;justify-content:space-between;gap:28px;flex-wrap:wrap;position:relative;overflow:hidden;}
.about-cta::after{content:"";position:absolute;right:-70px;top:-90px;width:230px;height:230px;border-radius:50%;border:1px solid var(--line-dark);}
.about-cta h2{color:#fff;font-size:clamp(24px,3vw,34px);margin-top:10px;}
.about-cta p{color:rgba(248,246,240,.72);margin-top:8px;max-width:620px;}
.about-cta > *{position:relative;z-index:1;}
@media (max-width:980px){
  .about-hero-grid,.about-editorial,.history-grid,.mandate-feature{grid-template-columns:1fr;}
  .about-hero-copy{padding-bottom:0;}
  .about-hero-card{border-radius:var(--radius-l);}
  .about-stat-strip{grid-template-columns:1fr 1fr;transform:none;margin-top:24px;}
  .about-overview{padding-top:72px;}
  .about-side-panel{position:relative;top:auto;}
  .values-premium-grid,.objectives-grid{grid-template-columns:1fr 1fr;}
  .mandate-media{min-height:280px;}
}
@media (max-width:620px){
  .about-hero{padding-top:28px;}
  .about-hero-copy h1{font-size:34px;}
  .about-stat-strip,.mv-premium-grid,.values-premium-grid,.objectives-grid{grid-template-columns:1fr;}
  .mandate-body,.mv-premium,.about-cta{padding:28px 24px;}
}
</style>
@endpush

@section('content')
<section class="page-hero about-hero">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current">About SIF</span></div>
    <div class="about-hero-grid">
      <div class="about-hero-copy reveal">
        <span class="eyebrow on-dark">About The Social Investment Fund</span>
        <h1>A national platform for pro-poor investment, built to endure.</h1>
        <p>Since 1998, SIF has helped Ghana move development finance from policy intent to visible community outcomes: infrastructure, enterprise support, skills, accountability and local capacity.</p>
        <div class="about-hero-actions">
          <a href="#mvv" class="btn btn-gold">Mission &amp; Values</a>
          <a href="/projects" class="btn btn-outline on-dark">Explore Projects</a>
        </div>
      </div>
      <div class="about-hero-card reveal" style="transition-delay:120ms;">
        <div class="about-hero-img">
          <img src="https://sifinghana.org/backend/images/uploads/_ENN6076.jpg1738337029.jpg" alt="SIF infrastructure project site" data-fallback loading="eager">
          <div class="about-hero-caption">
            <strong>Implementation with public accountability</strong>
            Fund Management Unit, Accra · Four operational zones nationwide
          </div>
        </div>
      </div>
    </div>
    <div class="about-stat-strip reveal-stagger">
      <div class="about-stat reveal" style="--i:0"><strong>1998</strong><span>Incorporated as an independent development institution</span></div>
      <div class="about-stat reveal" style="--i:1"><strong>16</strong><span>Regions reached through national and zonal operations</span></div>
      <div class="about-stat reveal" style="--i:2"><strong>4</strong><span>Operational zones supporting community delivery</span></div>
      <div class="about-stat reveal" style="--i:3"><strong>27+</strong><span>Years of project implementation experience</span></div>
    </div>
  </div>
</section>

<section class="sec bg-white about-overview" id="overview">
  <div class="container">
    <div class="about-editorial">
      <div class="about-copy reveal">
        <span class="eyebrow">Institutional Overview</span>
        <h2>Resourcing Ghana&rsquo;s underserved communities with discipline, proximity and trust.</h2>
        <div class="about-kicker-line"></div>
        <p class="lead">SIF was formed by the Government of Ghana, the African Development Bank and the United Nations Development Programme as a dependable channel for poverty-reduction investment.</p>
        <p>The institution mobilises and manages resources for rural and urban development, then turns those resources into social and economic infrastructure, MSME support, skills development, institutional strengthening and consultancy services.</p>
        <p>Its operating model combines national governance with local proximity: an independent Board of Directors, a Fund Management Unit in Accra, and four operational zones that keep delivery close to communities, MMDAs, contractors and partner institutions.</p>
        <p>That combination of governance, field presence and implementation discipline has made SIF a trusted partner for government, development finance institutions, multilateral lenders and community institutions.</p>
      </div>
      <aside class="about-side-panel reveal" style="transition-delay:120ms;">
        <span class="eyebrow on-dark">How SIF Works</span>
        <h3>Fund management with ground-level delivery.</h3>
        <p>SIF&rsquo;s strength is not only raising resources. It is structuring, supervising and accounting for delivery in places where development investment must be both practical and trusted.</p>
        <div class="about-proof-list">
          <div class="about-proof"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 5 5L20 7"/></svg><span>Independent Board oversight and FMU management</span></div>
          <div class="about-proof"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 5 5L20 7"/></svg><span>Community participation and demand-driven project selection</span></div>
          <div class="about-proof"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 5 5L20 7"/></svg><span>Transparent reporting, procurement and monitoring systems</span></div>
        </div>
      </aside>
    </div>
  </div>
</section>

<section class="sec-tight bg-dim">
  <div class="container">
    <div class="mandate-feature reveal">
      <div class="mandate-media">
        <img src="https://sifinghana.org/backend/images/uploads/WhatsApp%20Image%202026-01-21%20at%204.15.48%20PM.jpeg1769013143.jpeg" alt="SIF community project handover" data-fallback loading="lazy">
      </div>
      <div class="mandate-body">
        <span class="eyebrow">Legal Mandate</span>
        <h2>Targeted assistance for deprived communities, delivered through accountable partnerships.</h2>
        <p>SIF works to reduce rural and urban poverty in Ghana by supporting deprived communities through community-based organisations, local government authorities, civil society and development partners. Its mandate is gender-sensitive, participatory, demand-driven and built around sustainable outcomes.</p>
        <div class="mandate-pills">
          <span>Fund Management</span>
          <span>Project Implementation</span>
          <span>Community Participation</span>
          <span>Partner Confidence</span>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="sec bg-white" id="history">
  <div class="container">
    <div class="history-grid">
      <div class="section-head reveal">
        <span class="eyebrow">Our History</span>
        <h2>Almost three decades of development delivery.</h2>
        <p>SIF has grown from a foundational poverty-reduction vehicle into a multi-programme implementation platform trusted with national and partner-financed initiatives.</p>
      </div>
      <div class="history-rail reveal-stagger">
        <div class="history-card reveal" style="--i:0"><span class="year">1998</span><h3>SIF Incorporated</h3><p>Formed by the Government of Ghana, AfDB and UNDP as an independent pro-poor development institution.</p></div>
        <div class="history-card reveal" style="--i:1"><span class="year">GPRP I &amp; II</span><h3>National Poverty Reduction Programming</h3><p>Delivered infrastructure and community investments through AfDB and OFID-supported phases.</p></div>
        <div class="history-card reveal" style="--i:2"><span class="year">IRDP &amp; UPRP</span><h3>Rural and Urban Delivery Models</h3><p>Expanded SIF&rsquo;s implementation model across district-level rural projects and deprived urban communities.</p></div>
        <div class="history-card reveal" style="--i:3"><span class="year">2023 - 2027</span><h3>PSDPEP</h3><p>Post-COVID-19 skills, productivity and MSME support financed by AfDB and the Government of Ghana.</p></div>
        <div class="history-card reveal" style="--i:4"><span class="year">2026 - 2028</span><h3>GWYESCO</h3><p>A results-based programme supporting women, youth employment and social cohesion in northern Ghana.</p></div>
      </div>
    </div>
  </div>
</section>

<section class="sec bg-dim" id="mvv">
  <div class="container">
    <div class="section-head reveal center">
      <span class="eyebrow">Mission, Vision &amp; Values</span>
      <h2>The principles behind every SIF engagement.</h2>
      <p>Development work succeeds when resources are managed with clarity, communities are respected, and partners can see how decisions are made.</p>
    </div>
    <div class="mv-premium-grid reveal-stagger">
      <div class="mv-premium reveal" style="--i:0">
        <span class="tag tag-ongoing">Vision</span>
        <h3>The lead institution for resource transfer in the sub-region.</h3>
        <p>To be the leading organisation in Ghana and the wider West African sub-region for transferring resources that empower communities toward sustainable development.</p>
      </div>
      <div class="mv-premium reveal" style="--i:1">
        <span class="tag tag-new">Mission</span>
        <h3>Mobilising resources and delivering dignity.</h3>
        <p>To mobilise resources from government, multilateral partners and consultancy services that give Ghana&rsquo;s poorest communities access to infrastructure, financial services and capacity building.</p>
      </div>
    </div>
    <div class="values-premium-grid reveal-stagger">
      <div class="value-premium reveal" style="--i:0"><div class="value-top"><span class="value-num">01</span><span class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 2 4 6v6c0 5 3.4 8.7 8 10 4.6-1.3 8-5 8-10V6l-8-4Z"/><path d="m9 12 2 2 4-4"/></svg></span></div><h4>Professionalism</h4><p>Due diligence, sound judgement and a high standard of excellence in every resource decision.</p></div>
      <div class="value-premium reveal" style="--i:1"><div class="value-top"><span class="value-num">02</span><span class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="3.4"/><path d="M2 12s3.6-6.5 10-6.5S22 12 22 12s-3.6 6.5-10 6.5S2 12 2 12Z"/></svg></span></div><h4>Transparency</h4><p>Open reporting, clear governance and honest communication with partners and communities.</p></div>
      <div class="value-premium reveal" style="--i:2"><div class="value-top"><span class="value-num">03</span><span class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="8" cy="9" r="3"/><circle cx="17" cy="9" r="3"/><path d="M2 20c0-3.3 2.7-6 6-6s6 2.7 6 6M11 20c0-3 2.4-5.4 5.4-5.4S22 17 22 20"/></svg></span></div><h4>Teamwork</h4><p>Mutually supportive collaboration across the institution, partners and field operations.</p></div>
      <div class="value-premium reveal" style="--i:3"><div class="value-top"><span class="value-num">04</span><span class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 21V11l9-7 9 7v10"/><path d="M9 21v-6h6v6"/></svg></span></div><h4>Participation</h4><p>Communities are consulted, included in decisions and given ownership of outcomes.</p></div>
      <div class="value-premium reveal" style="--i:4"><div class="value-top"><span class="value-num">05</span><span class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="9.5"/><path d="M2.5 12h19M12 2.5c2.6 2.6 4 6 4 9.5s-1.4 6.9-4 9.5c-2.6-2.6-4-6-4-9.5s1.4-6.9 4-9.5Z"/></svg></span></div><h4>Inclusiveness</h4><p>No discrimination on the basis of gender, age, faith, ability or belief.</p></div>
      <div class="value-premium reveal" style="--i:5"><div class="value-top"><span class="value-num">06</span><span class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 21s-7-4.6-7-10.6A5.4 5.4 0 0 1 12 6a5.4 5.4 0 0 1 7 4.4C19 16.4 12 21 12 21Z"/><path d="M12 6v9"/></svg></span></div><h4>Green Economy</h4><p>Sustainability, resilience, dignity and good governance guide project design.</p></div>
      <div class="value-premium reveal" style="--i:6"><div class="value-top"><span class="value-num">07</span><span class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M9 12a3 3 0 1 0 6 0 3 3 0 0 0-6 0Z"/><path d="M5 19c.7-2.6 3-4.5 7-4.5s6.3 1.9 7 4.5"/></svg></span></div><h4>Partnership</h4><p>Co-operation with government, development agencies and communities themselves.</p></div>
      <div class="value-premium value-download reveal" style="--i:7"><div class="value-top"><span class="value-num">PDF</span><span class="value-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 3v18M3 12h18"/></svg></span></div><h4>Corporate Profile</h4><p>Download SIF&rsquo;s official profile for a fuller institutional overview.</p><a href="https://sifinghana.org/images/DRAFT%20Social%20Investment%20Fund%20Corporate%20Profile.pdf" target="_blank" rel="noopener">View PDF <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></a></div>
    </div>
  </div>
</section>

<section class="sec bg-white" id="objectives">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Strategic Objectives</span>
      <h2>What SIF is working to achieve.</h2>
      <p>These objectives translate the institution&rsquo;s mandate into practical workstreams for project design, implementation and accountability.</p>
    </div>
    <div class="objectives-grid reveal-stagger">
      <div class="objective-card reveal" style="--i:0" data-num="01"><h4>Mobilise Development Finance</h4><p>Secure and manage resources from government and multilateral partners for poverty-reduction programming.</p></div>
      <div class="objective-card reveal" style="--i:1" data-num="02"><h4>Deliver Resilient Infrastructure</h4><p>Design and build social and economic infrastructure that withstands time and serves whole communities.</p></div>
      <div class="objective-card reveal" style="--i:2" data-num="03"><h4>Expand MSME Finance &amp; Skills</h4><p>Grow access to microcredit, mentorship and capacity building for women- and youth-led enterprises.</p></div>
      <div class="objective-card reveal" style="--i:3" data-num="04"><h4>Strengthen Transparency</h4><p>Maintain disciplined financial management, audit and public reporting across every programme.</p></div>
      <div class="objective-card reveal" style="--i:4" data-num="05"><h4>Advance Inclusive Development</h4><p>Ensure every programme is designed and delivered without discrimination on gender, age or ability.</p></div>
      <div class="objective-card reveal" style="--i:5" data-num="06"><h4>Build Local Capacity</h4><p>Equip MMDAs, contractors and community institutions to sustain results after a project closes.</p></div>
      <div class="objective-card reveal" style="--i:6" data-num="07"><h4>Embed Green Principles</h4><p>Apply sustainability and resilience thinking to every infrastructure and enterprise investment.</p></div>
      <div class="objective-card reveal" style="--i:7" data-num="08"><h4>Deepen Partner Confidence</h4><p>Remain the fund management partner of choice for DFIs, MMDAs and not-for-profit institutions.</p></div>
    </div>
  </div>
</section>

<section class="sec-tight bg-dim">
  <div class="container">
    <div class="about-cta reveal">
      <div>
        <span class="eyebrow on-dark">Continue Exploring</span>
        <h2>Meet the people and projects behind SIF&rsquo;s mandate.</h2>
        <p>Review SIF&rsquo;s governance, departments, zonal offices and current national programmes.</p>
      </div>
      <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <a href="/leadership" class="btn btn-gold">Leadership</a>
        <a href="/departments" class="btn btn-outline on-dark">Departments &amp; Zones</a>
        <a href="/projects" class="btn btn-outline on-dark">Projects</a>
      </div>
    </div>
  </div>
</section>

@endsection
