@extends('layouts.app')

@section('title', 'Departments & Units | SIF Ghana')
@section('description', 'Explore the departments, units, and zonal offices of the Social Investment Fund Ghana.')

@push('head')
<style>
.structure-section{position:relative;overflow:hidden;}
.structure-section::before{content:"";position:absolute;left:-160px;top:80px;width:340px;height:340px;border-radius:50%;background:var(--emerald-light);opacity:.55;}
.structure-section .container{position:relative;z-index:1;}
.structure-summary{display:grid;grid-template-columns:1.05fr .95fr;gap:64px;align-items:center;margin-bottom:38px;}
.structure-kpis{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.structure-kpi{background:#fff;border:1px solid var(--line);border-radius:var(--radius-m);padding:22px;box-shadow:var(--shadow-s);position:relative;overflow:hidden;}
.structure-kpi::after{content:"";position:absolute;right:-34px;top:-34px;width:92px;height:92px;border-radius:50%;background:var(--gold-soft);}
.structure-kpi strong{display:block;position:relative;font-family:var(--font-head);font-size:34px;line-height:1;color:var(--forest);z-index:1;}
.structure-kpi span{display:block;position:relative;margin-top:8px;color:var(--ink-soft);font-size:12.5px;line-height:1.45;z-index:1;}
.organogram-wrap{background:linear-gradient(135deg,#fff 0%,var(--cream) 100%);border:1px solid var(--line);border-radius:24px;padding:30px;box-shadow:var(--shadow-l);position:relative;overflow:hidden;}
.organogram-wrap::before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 16% 10%,rgba(214,167,44,.14),transparent 30%),radial-gradient(circle at 86% 24%,rgba(8,127,91,.12),transparent 34%);pointer-events:none;}
.organogram{position:relative;z-index:1;display:grid;gap:22px;}
.org-row{position:relative;display:flex;justify-content:center;gap:18px;flex-wrap:wrap;}
.org-row.has-line::before{content:"";position:absolute;top:-22px;left:50%;width:2px;height:22px;background:var(--line);}
.org-split{position:relative;display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;}
.org-split::before{content:"";position:absolute;top:-12px;left:25%;right:25%;height:2px;background:var(--line);}
.org-branch{position:relative;background:rgba(255,255,255,.78);border:1px solid var(--line);border-radius:var(--radius-l);padding:18px;box-shadow:0 16px 40px rgba(7,59,42,.07);}
.org-branch::before{content:"";position:absolute;top:-34px;left:50%;width:2px;height:34px;background:var(--line);}
.org-branch h3{font-size:15px;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;gap:10px;}
.org-branch h3 span{font-family:var(--font-body);font-size:10.5px;text-transform:uppercase;letter-spacing:.08em;color:var(--ink-soft);font-weight:800;}
.org-card{position:relative;background:#fff;border:1px solid var(--line);border-radius:var(--radius-m);padding:18px 20px;min-width:230px;box-shadow:var(--shadow-s);overflow:hidden;}
.org-card::after{content:"";position:absolute;right:-28px;bottom:-28px;width:86px;height:86px;border-radius:50%;background:var(--emerald-light);}
.org-card > *{position:relative;z-index:1;}
.org-card.dark{background:linear-gradient(rgba(7,59,42,0.78),rgba(7,59,42,0.78)),var(--sif-pattern) center/cover no-repeat,var(--forest);border-color:var(--forest);color:#fff;box-shadow:0 20px 48px rgba(7,59,42,.22);}
.org-card.dark h3,.org-card.dark strong{color:#fff;}
.org-card.dark p,.org-card.dark small{color:rgba(248,246,240,.72);}
.org-card.gold{background:linear-gradient(135deg,var(--gold),#E6C15D);border-color:transparent;color:var(--forest);}
.org-card.gold::after{background:rgba(255,255,255,.22);}
.org-card.emerald{background:linear-gradient(rgba(8,127,91,0.76),rgba(8,127,91,0.76)),var(--sif-pattern) center/cover no-repeat,var(--emerald);border-color:var(--emerald);color:#fff;}
.org-card.emerald h3,.org-card.emerald strong{color:#fff;}
.org-card.emerald p,.org-card.emerald small{color:rgba(248,246,240,.74);}
.org-label{display:inline-flex;align-items:center;gap:7px;font-size:10.5px;line-height:1;text-transform:uppercase;letter-spacing:.08em;font-weight:800;color:var(--emerald);margin-bottom:9px;}
.org-label::before{content:"";width:7px;height:7px;border-radius:50%;background:var(--gold);}
.org-card.dark .org-label,.org-card.emerald .org-label{color:var(--gold-light);}
.org-card strong{display:block;font-family:var(--font-head);font-size:18px;line-height:1.25;color:var(--forest);}
.org-card p{margin-top:7px;color:var(--ink-soft);font-size:12.8px;line-height:1.5;}
.org-card small{display:block;margin-top:10px;color:var(--ink-soft);font-size:11.5px;font-weight:700;}
.org-mini-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;}
.org-mini{background:#fff;border:1px solid var(--line);border-radius:14px;padding:14px;min-height:112px;position:relative;overflow:hidden;}
.org-mini::before{content:attr(data-num);position:absolute;right:10px;top:8px;font-family:var(--font-head);font-size:28px;font-weight:800;color:rgba(7,59,42,.06);line-height:1;}
.org-mini strong{display:block;font-family:var(--font-head);font-size:13.5px;line-height:1.25;color:var(--forest);position:relative;}
.org-mini span{display:block;margin-top:7px;color:var(--ink-soft);font-size:11.8px;line-height:1.45;position:relative;}
.org-zone-list{display:grid;gap:10px;}
.org-zone{display:grid;grid-template-columns:auto 1fr auto;gap:12px;align-items:center;background:#fff;border:1px solid var(--line);border-radius:14px;padding:12px 14px;border-left:4px solid var(--zc,var(--emerald));}
.org-zone b{width:30px;height:30px;border-radius:50%;display:grid;place-items:center;background:var(--cream-dim);font-family:var(--font-head);font-size:12px;color:var(--forest);}
.org-zone strong{font-family:var(--font-head);font-size:13px;color:var(--forest);}
.org-zone span{font-size:11.5px;color:var(--ink-soft);}
.org-zone em{font-style:normal;font-size:11px;font-weight:800;color:var(--ink-soft);text-transform:uppercase;letter-spacing:.06em;}
.org-note{display:flex;align-items:flex-start;gap:10px;margin-top:18px;padding:14px 16px;border-radius:var(--radius-m);background:rgba(8,127,91,.08);color:var(--ink-soft);font-size:12.8px;line-height:1.55;}
.org-note svg{width:18px;height:18px;color:var(--emerald);flex:none;margin-top:1px;}
@media (max-width:980px){
  .structure-summary,.org-split{grid-template-columns:1fr;}
  .org-split::before,.org-branch::before,.org-row.has-line::before{display:none;}
  .org-branch{padding:16px;}
}
@media (max-width:640px){
  .organogram-wrap{padding:18px;border-radius:var(--radius-l);}
  .structure-kpis,.org-mini-grid{grid-template-columns:1fr;}
  .org-card{min-width:0;width:100%;}
  .org-zone{grid-template-columns:auto 1fr;}
  .org-zone em{grid-column:2;}
}
</style>
@endpush

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><a href="/about">About</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current">Departments &amp; Units</span></div>
    <span class="eyebrow on-dark">Organisational Structure</span>
    <h1>Departments, units &amp; zonal offices.</h1>
    <p>How SIF is structured from the Fund Management Unit in Accra down to four operational zones reaching all sixteen regions.</p>
  </div>
</section>

<!-- STRUCTURE -->
<section class="sec bg-white structure-section" id="structure">
  <div class="container">
    <div class="structure-summary">
      <div class="intro-copy reveal">
        <span class="eyebrow">Organisational Structure</span>
        <h2 class="section-head" style="margin:14px 0 0;">One Fund Management Unit, four zones, eight functional areas.</h2>
        <p>SIF&rsquo;s Fund Management Unit (FMU) in Accra houses the Office of the CEO and seven supporting departments and units. Delivery is coordinated nationwide through four operational zones, each close enough to the ground to know what a community actually needs.</p>
        <p>The Board of Directors sits above the FMU, providing independent strategic oversight, while committees such as the PSDPEP Steering and Technical Committees provide dedicated programme-level governance.</p>
      </div>
      <div class="structure-kpis reveal-stagger">
        <div class="structure-kpi reveal" style="--i:0"><strong>8</strong><span>Functional areas supporting administration, finance, programmes and controls.</span></div>
        <div class="structure-kpi reveal" style="--i:1"><strong>4</strong><span>Operational zones covering Ghana&rsquo;s sixteen regions.</span></div>
        <div class="structure-kpi reveal" style="--i:2"><strong>2</strong><span>Programme committees strengthening PSDPEP governance and delivery.</span></div>
        <div class="structure-kpi reveal" style="--i:3"><strong>1</strong><span>Fund Management Unit coordinating national implementation from Accra.</span></div>
      </div>
    </div>

    <div class="organogram-wrap reveal" style="transition-delay:120ms;">
      <div class="organogram" aria-label="SIF organisational organogram">
        <div class="org-row">
          <div class="org-card dark">
            <span class="org-label">Strategic Oversight</span>
            <strong>Board of Directors</strong>
            <p>Sets institutional direction, approves major decisions and strengthens accountability.</p>
          </div>
        </div>

        <div class="org-row has-line">
          <div class="org-card gold">
            <span class="org-label">Programme Governance</span>
            <strong>PSDPEP Steering Committee</strong>
            <p>Reviews programme priorities, budgets, audit reports and high-level delivery decisions.</p>
          </div>
          <div class="org-card gold">
            <span class="org-label">Technical Quality</span>
            <strong>PSDPEP Technical Committee</strong>
            <p>Supports implementation quality, technical coordination and timely issue resolution.</p>
          </div>
        </div>

        <div class="org-row has-line">
          <div class="org-card emerald">
            <span class="org-label">Executive Leadership</span>
            <strong>Office of the CEO</strong>
            <p>Leads the Fund Management Unit and coordinates the departments, units and zonal offices.</p>
            <small>Fund Management Unit - Accra</small>
          </div>
        </div>

        <div class="org-split">
          <div class="org-branch">
            <h3>Departments &amp; Units <span>Functional delivery</span></h3>
            <div class="org-mini-grid">
              <div class="org-mini" data-num="01"><strong>Administration</strong><span>Operations, HR and institutional support.</span></div>
              <div class="org-mini" data-num="02"><strong>Finance</strong><span>Financial management, reporting and compliance.</span></div>
              <div class="org-mini" data-num="03"><strong>Procurement</strong><span>Transparent sourcing and contract processes.</span></div>
              <div class="org-mini" data-num="04"><strong>Monitoring &amp; Evaluation</strong><span>Results tracking, reporting and impact evidence.</span></div>
              <div class="org-mini" data-num="05"><strong>Micro Credit</strong><span>On-lending and financial inclusion for MSMEs.</span></div>
              <div class="org-mini" data-num="06"><strong>Internal Audit</strong><span>Independent assurance and risk management.</span></div>
            </div>
          </div>

          <div class="org-branch">
            <h3>Zonal Offices <span>National coverage</span></h3>
            <div class="org-zone-list">
              <div class="org-zone" style="--zc:var(--zone-a);"><b>01</b><div><strong>Savannah Belt</strong><span>Upper East, Upper West, North East, Savannah, Northern</span></div><em>5 regions</em></div>
              <div class="org-zone" style="--zc:var(--zone-b);"><b>02</b><div><strong>Forest &amp; Transition</strong><span>Ashanti, Bono, Bono East, Ahafo</span></div><em>4 regions</em></div>
              <div class="org-zone" style="--zc:var(--zone-c);"><b>03</b><div><strong>Western Coast</strong><span>Central, Western North, Western</span></div><em>3 regions</em></div>
              <div class="org-zone" style="--zc:var(--zone-d);"><b>04</b><div><strong>Eastern Seaboard</strong><span>Eastern, Volta, Oti, Greater Accra</span></div><em>4 regions</em></div>
            </div>
          </div>
        </div>

        <div class="org-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
          <span>This organogram is a simplified public-facing view of SIF&rsquo;s structure, showing governance, executive management, functional departments and zonal delivery coverage.</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- DEPARTMENTS -->
<section class="sec bg-dim" id="departments">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Departments &amp; Units</span>
      <h2>Eight functional areas, one institution.</h2>
    </div>
    <div class="dept-grid reveal-stagger">
      <div class="dept-cell reveal" style="--i:0"><span class="dnum">01</span><h4>CEO&rsquo;s Office</h4><p>Strategic direction and oversight of the institution.</p></div>
      <div class="dept-cell reveal" style="--i:1"><span class="dnum">02</span><h4>Administration</h4><p>Day-to-day operations, HR and institutional support.</p></div>
      <div class="dept-cell reveal" style="--i:2"><span class="dnum">03</span><h4>Finance</h4><p>Financial management, reporting and donor compliance.</p></div>
      <div class="dept-cell reveal" style="--i:3"><span class="dnum">04</span><h4>Procurement</h4><p>Transparent, competitive sourcing for every project.</p></div>
      <div class="dept-cell reveal" style="--i:4"><span class="dnum">05</span><h4>Monitoring &amp; Evaluation</h4><p>Tracking results and proving impact on the ground.</p></div>
      <div class="dept-cell reveal" style="--i:5"><span class="dnum">06</span><h4>Micro Credit</h4><p>On-lending and financial inclusion for MSMEs.</p></div>
      <div class="dept-cell reveal" style="--i:6"><span class="dnum">07</span><h4>Internal Audit</h4><p>Independent assurance and institutional risk management.</p></div>
      <div class="dept-cell reveal" style="--i:7"><span class="dnum">08</span><h4>Zonal Offices</h4><p>Local presence across all four operational zones.</p></div>
    </div>
  </div>
</section>

<!-- ZONES -->
<section class="sec bg-forest" id="zones">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow on-dark">Zonal Offices</span>
      <h2 style="color:#fff;">Four zones. Sixteen regions. One mission.</h2>
      <p style="color:rgba(248,246,240,0.78);">SIF&rsquo;s Fund Management Unit in Accra coordinates delivery nationwide through four operational zones — schematic, north to coast.</p>
    </div>
    <div class="zone-list reveal-stagger">
      <div class="zone-card reveal" style="--i:0;--zc:var(--zone-a);">
        <span class="zone-num">01</span>
        <div class="zone-info"><h3>Savannah Belt</h3><span class="zsub">Northernmost zone</span><div class="zone-regions"><span>Upper East</span><span>Upper West</span><span>North East</span><span>Savannah</span><span>Northern</span></div></div>
        <div class="zone-count"><strong>5</strong><span>Regions</span></div>
      </div>
      <div class="zone-card reveal" style="--i:1;--zc:var(--zone-b);">
        <span class="zone-num">02</span>
        <div class="zone-info"><h3>Forest &amp; Transition</h3><span class="zsub">Mid-northern zone</span><div class="zone-regions"><span>Ashanti</span><span>Bono</span><span>Bono East</span><span>Ahafo</span></div></div>
        <div class="zone-count"><strong>4</strong><span>Regions</span></div>
      </div>
      <div class="zone-card reveal" style="--i:2;--zc:var(--zone-c);">
        <span class="zone-num">03</span>
        <div class="zone-info"><h3>Western Coast</h3><span class="zsub">South-western zone</span><div class="zone-regions"><span>Central</span><span>Western North</span><span>Western</span></div></div>
        <div class="zone-count"><strong>3</strong><span>Regions</span></div>
      </div>
      <div class="zone-card reveal" style="--i:3;--zc:var(--zone-d);">
        <span class="zone-num">04</span>
        <div class="zone-info"><h3>Eastern Seaboard</h3><span class="zsub">Coast &amp; Fund Management Unit · Accra</span><div class="zone-regions"><span>Eastern</span><span>Volta</span><span>Oti</span><span>Greater Accra</span></div></div>
        <div class="zone-count"><strong>4</strong><span>Regions</span></div>
      </div>
    </div>
    <div class="zones-foot reveal" style="margin-top:24px;display:flex;align-items:center;gap:10px;color:rgba(248,246,240,0.6);font-size:13px;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" width="18" height="18"><circle cx="12" cy="12" r="9.5"/><path d="M12 7v5l3.5 2"/></svg>
      Schematic representation, not to scale — every one of Ghana&rsquo;s 16 regions sits within one of these four operational zones.
    </div>
  </div>
</section>

<!-- COMMITTEES -->
<section class="sec-tight bg-dim" id="committees">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Committees</span>
      <h2>Dedicated oversight for active programmes.</h2>
    </div>
    <div class="dept-grid reveal-stagger" style="grid-template-columns:1fr 1fr;">
      <div class="dept-cell reveal" style="--i:0"><span class="dnum">Governance</span><h4>PSDPEP Steering Committee</h4><p>Senior representatives from the Ministry of Finance, AfDB and partner institutions who approve budgets, work plans and audit reports for the PSDPEP.</p></div>
      <div class="dept-cell reveal" style="--i:1"><span class="dnum">Delivery</span><h4>PSDPEP Technical Committee</h4><p>Technical leads who oversee day-to-day implementation quality and resolve delivery challenges as they arise on the ground.</p></div>
    </div>
  </div>
</section>

@endsection
