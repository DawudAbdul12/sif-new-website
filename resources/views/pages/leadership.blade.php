@extends('layouts.app')

@section('title', 'Leadership | The Social Investment Fund Ghana')
@section('description', 'Meet the Board of Directors, Chief Executive Officer, and Management Team of SIF Ghana.')

@push('head')
<style>
.leadership-intro{display:grid;grid-template-columns:minmax(0,1fr) 360px;gap:40px;align-items:stretch;}
.governance-card{background:#fff;border:1px solid var(--line);border-radius:var(--radius-l);box-shadow:var(--shadow-s);overflow:hidden;display:grid;grid-template-columns:320px minmax(0,1fr);}
.governance-mark{background:linear-gradient(rgba(7,59,42,0.78),rgba(7,59,42,0.78)),var(--sif-pattern) center/cover no-repeat,var(--forest);color:#fff;padding:34px;display:flex;flex-direction:column;justify-content:space-between;min-height:300px;position:relative;overflow:hidden;}
.governance-mark::after{content:"";position:absolute;right:-70px;top:-70px;width:220px;height:220px;border-radius:50%;border:1px solid var(--line-dark);}
.governance-mark strong{font-family:var(--font-head);font-size:clamp(42px,6vw,72px);line-height:1;color:var(--gold-light);position:relative;z-index:1;}
.governance-mark span{font-size:12px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:rgba(248,246,240,.72);position:relative;z-index:1;}
.governance-body{padding:34px 38px;display:flex;flex-direction:column;justify-content:center;}
.governance-body h3{font-size:clamp(22px,2.5vw,32px);line-height:1.15;margin-bottom:12px;}
.governance-body p{color:var(--ink-soft);font-size:15px;line-height:1.75;max-width:700px;}
.governance-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:24px;}
.leadership-proof{background:var(--cream-dim);border:1px solid var(--line);border-radius:var(--radius-l);padding:26px;}
.proof-item{display:flex;gap:12px;align-items:flex-start;padding:16px 0;border-top:1px solid var(--line);}
.proof-item:first-child{border-top:none;padding-top:0;}
.proof-item svg{width:18px;height:18px;color:var(--emerald);flex:none;margin-top:2px;}
.proof-item strong{display:block;font-family:var(--font-head);font-size:14px;color:var(--forest);}
.proof-item span{display:block;font-size:12.5px;color:var(--ink-soft);line-height:1.55;margin-top:2px;}
.ceo-feature{display:grid;grid-template-columns:360px minmax(0,1fr);gap:0;background:#fff;border:1px solid var(--line);border-radius:var(--radius-l);overflow:hidden;box-shadow:var(--shadow-m);}
.ceo-photo{position:relative;min-height:430px;background:var(--cream-dim);overflow:hidden;}
.ceo-photo img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;}
.ceo-photo::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,transparent 55%,rgba(7,59,42,.78));}
.ceo-badge{position:absolute;left:18px;right:18px;bottom:18px;z-index:1;color:#fff;}
.ceo-badge span{display:inline-flex;background:var(--gold);color:var(--forest);font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;border-radius:var(--radius-full);padding:6px 10px;margin-bottom:8px;}
.ceo-badge strong{display:block;font-family:var(--font-head);font-size:22px;line-height:1.15;}
.ceo-content{padding:42px;display:flex;flex-direction:column;justify-content:center;}
.ceo-content h2{font-size:clamp(26px,3vw,40px);line-height:1.12;margin:12px 0 14px;}
.ceo-content p{color:var(--ink-soft);font-size:15px;line-height:1.75;}
.ceo-highlights{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:28px;}
.ceo-highlight{background:var(--cream-dim);border:1px solid var(--line);border-radius:var(--radius-m);padding:16px;}
.ceo-highlight strong{display:block;font-family:var(--font-head);font-size:18px;color:var(--forest);}
.ceo-highlight span{display:block;font-size:11.5px;color:var(--ink-soft);line-height:1.45;margin-top:3px;}
.management-head{display:flex;align-items:flex-end;justify-content:space-between;gap:28px;margin-bottom:34px;}
.management-head .section-head{margin-bottom:0;}
.management-note{max-width:360px;background:var(--emerald-light);border:1px solid rgba(8,127,91,.16);border-radius:var(--radius-m);padding:18px 20px;color:var(--forest);font-size:13.5px;line-height:1.6;}
.premium-team-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;}
.premium-team-card{background:#fff;border:1px solid var(--line);border-radius:var(--radius-m);overflow:hidden;box-shadow:var(--shadow-s);transition:transform .22s var(--ease),box-shadow .22s var(--ease),border-color .22s;}
.premium-team-card:hover{transform:translateY(-5px);box-shadow:var(--shadow-m);border-color:transparent;}
.premium-team-photo{position:relative;aspect-ratio:4/4.6;background:linear-gradient(135deg,var(--emerald-light),var(--gold-soft));overflow:hidden;}
.premium-team-photo img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;}
.premium-team-photo::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,transparent 52%,rgba(7,59,42,.72));opacity:.9;}
.premium-team-role{position:absolute;left:14px;right:14px;bottom:14px;z-index:1;color:#fff;font-size:11px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;line-height:1.35;}
.premium-team-body{padding:18px 18px 20px;}
.premium-team-body h3{font-size:16px;line-height:1.25;margin-bottom:8px;}
.premium-team-body p{font-size:13px;color:var(--ink-soft);line-height:1.62;}
.leadership-cta{background:linear-gradient(rgba(7,59,42,0.78),rgba(7,59,42,0.78)),var(--sif-pattern) center/cover no-repeat,var(--forest);border-radius:var(--radius-l);padding:42px 46px;display:flex;align-items:center;justify-content:space-between;gap:28px;flex-wrap:wrap;position:relative;overflow:hidden;}
.leadership-cta::after{content:"";position:absolute;right:-70px;top:-90px;width:230px;height:230px;border-radius:50%;border:1px solid var(--line-dark);}
.leadership-cta h2{color:#fff;font-size:clamp(24px,3vw,34px);margin-top:10px;}
.leadership-cta p{color:rgba(248,246,240,.72);max-width:620px;margin-top:8px;}
.leadership-cta > *{position:relative;z-index:1;}
@media (max-width:1100px){.premium-team-grid{grid-template-columns:repeat(3,1fr);}}
@media (max-width:920px){
  .leadership-intro,.governance-card,.ceo-feature{grid-template-columns:1fr;}
  .governance-mark{min-height:220px;}
  .ceo-photo{min-height:360px;}
  .management-head{align-items:flex-start;flex-direction:column;}
  .premium-team-grid{grid-template-columns:1fr 1fr;}
}
@media (max-width:560px){
  .governance-body,.ceo-content,.leadership-cta{padding:28px 24px;}
  .ceo-highlights,.premium-team-grid{grid-template-columns:1fr;}
}
</style>
@endpush

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><a href="/about">About</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current">Leadership</span></div>
    <span class="eyebrow on-dark">Leadership &amp; Structure</span>
    <h1>Governed for accountability.</h1>
    <p>Independent oversight, experienced leadership, and a unit structure built to deliver across every region of Ghana.</p>
  </div>
</section>

<!-- CEO -->
<section class="sec bg-white" id="ceo">
  <div class="container">
    <div class="ceo-feature reveal">
      <div class="ceo-photo">
        <img src="https://sifinghana.org/backend/images/uploads/IMG-20250124-WA0101.jpg1738238916.jpg" alt="Abass Nurudeen, ESQ" data-fallback loading="lazy">
        <div class="ceo-badge">
          <span>Chief Executive Officer</span>
          <strong>Abass Nurudeen, ESQ</strong>
        </div>
      </div>
      <div class="ceo-content">
        <span class="eyebrow">Executive Leadership</span>
        <h2>Coordinating strategy, delivery and partner confidence.</h2>
        <p>The Office of the CEO provides strategic direction and day-to-day oversight of the institution, coordinating across Finance, Procurement, Monitoring &amp; Evaluation, Micro Credit, Internal Audit, Administration and all four zonal offices.</p>
        <p style="margin-top:14px;">Under his leadership, SIF has overseen the launch of GWYESCO — Ghana&rsquo;s first AfDB results-based financing operation — and the continued delivery of PSDPEP, IRDP II and SIF&rsquo;s broader poverty-reduction portfolio.</p>
        <div class="ceo-highlights">
          <div class="ceo-highlight"><strong>4</strong><span>Operational zones coordinated nationwide</span></div>
          <div class="ceo-highlight"><strong>6</strong><span>Flagship programmes in the institutional portfolio</span></div>
          <div class="ceo-highlight"><strong>16</strong><span>Regions reached through SIF delivery systems</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- MANAGEMENT -->
<section class="sec bg-white" id="management">
  <div class="container">
    <div class="management-head reveal">
      <div class="section-head">
        <span class="eyebrow">Senior Leadership</span>
        <h2>Management Team</h2>
        <p>A multidisciplinary leadership team spanning executive leadership, finance, procurement, monitoring, field coordination and administration.</p>
      </div>
      <div class="management-note">Profiles and photographs are sourced from SIF&rsquo;s current management listing and presented here in the site&rsquo;s refreshed visual system.</div>
    </div>
    <div class="premium-team-grid reveal-stagger">
      <article class="premium-team-card reveal" style="--i:0"><div class="premium-team-photo"><img src="https://sifinghana.org/backend/images/uploads/for_website_2.jpg1742986137-removebg-preview.png1743151474.png" alt="Prosper Puo-Ire" data-fallback loading="lazy"><div class="premium-team-role">Deputy CEO</div></div><div class="premium-team-body"><h3>Prosper Puo-Ire</h3><p>Supports executive coordination, development administration and institutional delivery.</p></div></article>
      <article class="premium-team-card reveal" style="--i:1"><div class="premium-team-photo"><img src="https://sifinghana.org/backend/images/uploads/Paa%20Yaw%202.jpg1757338869.jpg" alt="Paa Yaw Arkoh-Koomson" data-fallback loading="lazy"><div class="premium-team-role">Director for Finance and Accounting</div></div><div class="premium-team-body"><h3>Paa Yaw Arkoh-Koomson</h3><p>Leads financial management, accounting and compliance across SIF programmes.</p></div></article>
      <article class="premium-team-card reveal" style="--i:2"><div class="premium-team-photo"><img src="https://sifinghana.org/backend/images/uploads/Agbesi%20pic.JPG1742914873.JPG" alt="Kwaku Agbesi, PhD" data-fallback loading="lazy"><div class="premium-team-role">Procurement Specialist</div></div><div class="premium-team-body"><h3>Kwaku Agbesi, PhD</h3><p>Oversees procurement processes for transparent sourcing of goods, works and services.</p></div></article>
      <article class="premium-team-card reveal" style="--i:3"><div class="premium-team-photo"><img src="https://sifinghana.org/backend/images/uploads/macD.jpg1742389371.jpg" alt="MacDonald Acquah" data-fallback loading="lazy"><div class="premium-team-role">Monitoring &amp; Evaluation Specialist</div></div><div class="premium-team-body"><h3>MacDonald Acquah</h3><p>Tracks implementation progress, results and impact across SIF interventions.</p></div></article>
      <article class="premium-team-card reveal" style="--i:4"><div class="premium-team-photo"><img src="https://sifinghana.org/backend/images/uploads/heinz%20111.jpg1741883521.jpg" alt="Heinz Osei Karikari" data-fallback loading="lazy"><div class="premium-team-role">Zonal Coordinator - Zone 2</div></div><div class="premium-team-body"><h3>Heinz Osei Karikari</h3><p>Coordinates zonal delivery, local partnerships and field-level implementation support.</p></div></article>
      <article class="premium-team-card reveal" style="--i:5"><div class="premium-team-photo"><img src="https://sifinghana.org/backend/images/uploads/Togbe%202.jpg1742388941.jpg" alt="Moses Kwame Ohene" data-fallback loading="lazy"><div class="premium-team-role">Zonal Coordinator - Zone 3</div></div><div class="premium-team-body"><h3>Moses Kwame Ohene</h3><p>Supports project coordination, community engagement and zonal operations.</p></div></article>
      <article class="premium-team-card reveal" style="--i:6"><div class="premium-team-photo"><img src="https://sifinghana.org/backend/images/uploads/OFOSU%201.JPG1741867679.JPG" alt="Joseph Ofosu-Kwarteng" data-fallback loading="lazy"><div class="premium-team-role">Zonal Coordinator - Zone 4</div></div><div class="premium-team-body"><h3>Joseph Ofosu-Kwarteng</h3><p>Coordinates field operations and stakeholder support across assigned programme areas.</p></div></article>
      <article class="premium-team-card reveal" style="--i:7"><div class="premium-team-photo"><img src="https://sifinghana.org/backend/images/uploads/IMG-20240924-WA0009.jpg1727182387.jpg" alt="Stella Arthur" data-fallback loading="lazy"><div class="premium-team-role">Administrative Officer</div></div><div class="premium-team-body"><h3>Stella Arthur</h3><p>Provides administrative coordination and institutional support for SIF operations.</p></div></article>
    </div>
  </div>
</section>

<section class="sec-tight bg-dim">
  <div class="container">
    <div class="leadership-cta reveal">
      <div>
        <span class="eyebrow on-dark">Organisational Structure</span>
        <h2>See how departments and zonal offices fit together.</h2>
        <p>Explore the departments, units and field structure that support SIF&rsquo;s delivery across Ghana.</p>
      </div>
      <a href="/departments" class="btn btn-gold">Departments &amp; Units
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
      </a>
    </div>
  </div>
</section>

@endsection
