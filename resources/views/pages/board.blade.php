@extends('layouts.app')

@section('title', 'Board of Directors | The Social Investment Fund Ghana')
@section('description', 'Learn about the Board of Directors of the Social Investment Fund Ghana and its role in strategic oversight, accountability and governance.')

@push('head')
<style>
.board-overview{display:grid;grid-template-columns:minmax(0,1fr) 360px;gap:40px;align-items:stretch;}
.board-governance-card{background:#fff;border:1px solid var(--line);border-radius:var(--radius-l);box-shadow:var(--shadow-s);overflow:hidden;display:grid;grid-template-columns:320px minmax(0,1fr);}
.board-mark{background:linear-gradient(rgba(7,59,42,0.78),rgba(7,59,42,0.78)),var(--sif-pattern) center/cover no-repeat,var(--forest);color:#fff;padding:34px;display:flex;flex-direction:column;justify-content:space-between;min-height:320px;position:relative;overflow:hidden;}
.board-mark::after{content:"";position:absolute;right:-76px;top:-76px;width:230px;height:230px;border-radius:50%;border:1px solid var(--line-dark);}
.board-mark span{font-size:12px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:rgba(248,246,240,.72);position:relative;z-index:1;}
.board-mark strong{font-family:var(--font-head);font-size:clamp(48px,7vw,78px);line-height:1;color:var(--gold-light);position:relative;z-index:1;}
.board-card-body{padding:38px;display:flex;flex-direction:column;justify-content:center;}
.board-card-body h2{font-size:clamp(26px,3vw,40px);line-height:1.12;margin:14px 0;}
.board-card-body p{color:var(--ink-soft);font-size:15.5px;line-height:1.75;max-width:760px;}
.board-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px;}
.board-proof{background:var(--cream-dim);border:1px solid var(--line);border-radius:var(--radius-l);padding:26px;}
.board-proof-item{display:flex;gap:12px;align-items:flex-start;padding:16px 0;border-top:1px solid var(--line);}
.board-proof-item:first-child{border-top:none;padding-top:0;}
.board-proof-item svg{width:18px;height:18px;color:var(--emerald);flex:none;margin-top:2px;}
.board-proof-item strong{display:block;font-family:var(--font-head);font-size:14px;color:var(--forest);}
.board-proof-item span{display:block;font-size:12.5px;color:var(--ink-soft);line-height:1.55;margin-top:2px;}
.board-responsibilities{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;}
.board-responsibility{background:#fff;border:1px solid var(--line);border-radius:var(--radius-m);padding:24px;position:relative;overflow:hidden;box-shadow:var(--shadow-s);}
.board-responsibility::before{content:attr(data-num);position:absolute;right:18px;top:12px;font-family:var(--font-head);font-size:42px;font-weight:800;color:rgba(7,59,42,.06);line-height:1;}
.board-responsibility h3{font-size:16px;line-height:1.3;margin-bottom:10px;position:relative;}
.board-responsibility p{color:var(--ink-soft);font-size:13.5px;line-height:1.62;position:relative;}
.board-profile-head{display:flex;align-items:flex-end;justify-content:space-between;gap:28px;margin-bottom:34px;}
.board-profile-head .section-head{margin-bottom:0;}
.board-profile-note{max-width:380px;background:var(--emerald-light);border:1px solid rgba(8,127,91,.16);border-radius:var(--radius-m);padding:18px 20px;color:var(--forest);font-size:13.5px;line-height:1.6;}
.board-chair-card{display:grid;grid-template-columns:360px minmax(0,1fr);background:#fff;border:1px solid var(--line);border-radius:var(--radius-l);overflow:hidden;box-shadow:var(--shadow-m);margin-bottom:24px;}
.board-chair-photo{position:relative;min-height:420px;background:linear-gradient(135deg,var(--emerald-light),var(--gold-soft));overflow:hidden;display:flex;align-items:center;justify-content:center;}
.board-chair-photo::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,transparent 45%,rgba(7,59,42,.82));}
.board-avatar{width:132px;height:132px;border-radius:50%;background:#fff;border:1px solid var(--line);box-shadow:var(--shadow-m);display:flex;align-items:center;justify-content:center;font-family:var(--font-head);font-size:42px;font-weight:800;color:var(--forest);position:relative;z-index:1;}
.board-chair-label{position:absolute;left:20px;right:20px;bottom:20px;z-index:1;color:#fff;}
.board-chair-label span{display:inline-flex;background:var(--gold);color:var(--forest);font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;border-radius:var(--radius-full);padding:6px 10px;margin-bottom:8px;}
.board-chair-label strong{display:block;font-family:var(--font-head);font-size:22px;line-height:1.15;}
.board-chair-body{padding:42px;display:flex;flex-direction:column;justify-content:center;}
.board-chair-body h3{font-size:clamp(26px,3vw,40px);line-height:1.12;margin:12px 0;}
.board-chair-body p{color:var(--ink-soft);font-size:15px;line-height:1.75;max-width:720px;}
.board-profile-meta{display:flex;gap:8px;flex-wrap:wrap;margin-top:22px;}
.board-profile-meta span{background:var(--cream-dim);border:1px solid var(--line);border-radius:var(--radius-full);padding:7px 12px;font-size:12px;font-weight:700;color:var(--forest);}
.board-member-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;}
.board-member-card{background:#fff;border:1px solid var(--line);border-radius:var(--radius-m);overflow:hidden;box-shadow:var(--shadow-s);transition:transform .22s var(--ease),box-shadow .22s var(--ease),border-color .22s;}
.board-member-card:hover{transform:translateY(-5px);box-shadow:var(--shadow-m);border-color:transparent;}
.board-member-photo{position:relative;aspect-ratio:4/4.6;background:linear-gradient(135deg,var(--emerald-light),var(--gold-soft));overflow:hidden;display:flex;align-items:center;justify-content:center;}
.board-member-photo::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(7,59,42,.72));}
.board-member-photo .board-avatar{width:92px;height:92px;font-size:30px;box-shadow:var(--shadow-s);}
.board-member-role{position:absolute;left:14px;right:14px;bottom:14px;z-index:1;color:#fff;font-size:11px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;line-height:1.35;}
.board-member-body{padding:18px 18px 20px;}
.board-member-body h3{font-size:16px;line-height:1.25;margin-bottom:8px;}
.board-member-body p{font-size:13px;color:var(--ink-soft);line-height:1.62;}
.board-empty-note{margin-top:24px;background:var(--gold-soft);border:1px solid rgba(214,167,44,.28);border-radius:var(--radius-m);padding:18px 20px;color:#7A5A0E;font-size:13.5px;line-height:1.6;}
.board-cta{background:linear-gradient(rgba(7,59,42,0.78),rgba(7,59,42,0.78)),var(--sif-pattern) center/cover no-repeat,var(--forest);border-radius:var(--radius-l);padding:42px 46px;display:flex;align-items:center;justify-content:space-between;gap:28px;flex-wrap:wrap;position:relative;overflow:hidden;}
.board-cta::after{content:"";position:absolute;right:-70px;top:-90px;width:230px;height:230px;border-radius:50%;border:1px solid var(--line-dark);}
.board-cta h2{color:#fff;font-size:clamp(24px,3vw,34px);margin-top:10px;}
.board-cta p{color:rgba(248,246,240,.72);max-width:620px;margin-top:8px;}
.board-cta > *{position:relative;z-index:1;}
@media (max-width:980px){
  .board-overview,.board-governance-card,.board-chair-card{grid-template-columns:1fr;}
  .board-mark{min-height:220px;}
  .board-responsibilities,.board-member-grid{grid-template-columns:1fr 1fr;}
  .board-profile-head{align-items:flex-start;flex-direction:column;}
  .board-chair-photo{min-height:320px;}
}
@media (max-width:560px){
  .board-card-body,.board-chair-body,.board-cta{padding:28px 24px;}
  .board-responsibilities,.board-member-grid{grid-template-columns:1fr;}
}
</style>
@endpush

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><a href="/about">About</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current">Board of Directors</span></div>
    <span class="eyebrow on-dark">Governance</span>
    <h1>Board of Directors</h1>
    <p>Independent oversight, strategic direction and institutional accountability for SIF&rsquo;s national development mandate.</p>
  </div>
</section>

<section class="sec bg-white">
  <div class="container">
    <div class="board-overview">
      <div class="board-governance-card reveal">
        <div class="board-mark">
          <span>Governing Body</span>
          <strong>BoD</strong>
          <span>Strategic oversight</span>
        </div>
        <div class="board-card-body">
          <span class="eyebrow">Governance Role</span>
          <h2>Oversight that keeps development finance accountable.</h2>
          <p>The Board of Directors provides strategic oversight for SIF, approves major institutional decisions, reviews budgets and audit reports, and holds management accountable for the delivery of SIF&rsquo;s pro-poor development mandate.</p>
          <div class="board-actions">
            <a href="/resources#reports" class="btn btn-primary">Annual Reports</a>
            <a href="/leadership" class="btn btn-outline">Management Team</a>
          </div>
        </div>
      </div>
      <aside class="board-proof reveal" style="transition-delay:120ms;">
        <div class="board-proof-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 5 5L20 7"/></svg>
          <div><strong>Strategic direction</strong><span>Guides institutional priorities and long-range development focus.</span></div>
        </div>
        <div class="board-proof-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 5 5L20 7"/></svg>
          <div><strong>Financial accountability</strong><span>Reviews budgets, audits and institutional controls.</span></div>
        </div>
        <div class="board-proof-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 5 5L20 7"/></svg>
          <div><strong>Public trust</strong><span>Supports transparent delivery for partners and beneficiary communities.</span></div>
        </div>
      </aside>
    </div>
  </div>
</section>

<section class="sec bg-dim">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Board Responsibilities</span>
      <h2>What the Board oversees.</h2>
      <p>The Board&rsquo;s work sits above programme implementation, ensuring that the Fund Management Unit and departments deliver within the mandate, controls and expectations of SIF.</p>
    </div>
    <div class="board-responsibilities reveal-stagger">
      <div class="board-responsibility reveal" style="--i:0" data-num="01"><h3>Institutional Strategy</h3><p>Sets direction for SIF&rsquo;s mandate, priorities and long-term institutional development.</p></div>
      <div class="board-responsibility reveal" style="--i:1" data-num="02"><h3>Financial Oversight</h3><p>Reviews budget performance, audit outcomes and financial accountability systems.</p></div>
      <div class="board-responsibility reveal" style="--i:2" data-num="03"><h3>Programme Accountability</h3><p>Holds management accountable for project delivery, reporting and partner commitments.</p></div>
      <div class="board-responsibility reveal" style="--i:3" data-num="04"><h3>Governance Assurance</h3><p>Supports policies, controls and structures that protect public and partner confidence.</p></div>
    </div>
  </div>
</section>

<section class="sec bg-white">
  <div class="container">
    <div class="board-profile-head reveal">
      <div class="section-head">
        <span class="eyebrow">Board Member Profiles</span>
        <h2>A profile system ready for official board names and photographs.</h2>
        <p>The design below shows how board profiles will appear once individual member details are available from official SIF sources.</p>
      </div>
      <div class="board-profile-note">The live SIF Board page currently publishes the Board of Directors heading without individual member cards, so these are structured profile placeholders.</div>
    </div>

    <article class="board-chair-card reveal">
      <div class="board-chair-photo">
        <div class="board-avatar">BoD</div>
        <div class="board-chair-label">
          <span>Chairperson</span>
          <strong>Board Chairperson</strong>
        </div>
      </div>
      <div class="board-chair-body">
        <span class="eyebrow">Featured Profile</span>
        <h3>Name to be published</h3>
        <p>The Chairperson profile can introduce the Board&rsquo;s leadership role, governance focus and strategic oversight responsibilities. When an official photograph is available, it can replace the monogram while preserving the same layout.</p>
        <div class="board-profile-meta">
          <span>Strategic Oversight</span>
          <span>Governance Leadership</span>
          <span>Public Accountability</span>
        </div>
      </div>
    </article>

    <div class="board-member-grid reveal-stagger">
      <article class="board-member-card reveal" style="--i:0">
        <div class="board-member-photo"><div class="board-avatar">01</div><div class="board-member-role">Board Member</div></div>
        <div class="board-member-body"><h3>Member Name</h3><p>Short governance bio, represented institution, committee role or area of oversight can appear here.</p></div>
      </article>
      <article class="board-member-card reveal" style="--i:1">
        <div class="board-member-photo"><div class="board-avatar">02</div><div class="board-member-role">Board Member</div></div>
        <div class="board-member-body"><h3>Member Name</h3><p>Use this space for official profile notes once confirmed by SIF or published annual reports.</p></div>
      </article>
      <article class="board-member-card reveal" style="--i:2">
        <div class="board-member-photo"><div class="board-avatar">03</div><div class="board-member-role">Board Member</div></div>
        <div class="board-member-body"><h3>Member Name</h3><p>The card supports names, roles, affiliations and a concise accountability-focused biography.</p></div>
      </article>
      <article class="board-member-card reveal" style="--i:3">
        <div class="board-member-photo"><div class="board-avatar">04</div><div class="board-member-role">Board Member</div></div>
        <div class="board-member-body"><h3>Member Name</h3><p>Replace this placeholder with official details when board composition is published.</p></div>
      </article>
    </div>

    <div class="board-empty-note reveal">Once official board member information is supplied, each placeholder can be swapped for a real name, role, image and short profile without changing the page structure.</div>
  </div>
</section>

<section class="sec-tight bg-dim">
  <div class="container">
    <div class="board-cta reveal">
      <div>
        <span class="eyebrow on-dark">Leadership &amp; Structure</span>
        <h2>Explore the executive team and operating structure.</h2>
        <p>See how the CEO, management team, departments and zonal offices support SIF&rsquo;s work across Ghana.</p>
      </div>
      <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <a href="/leadership" class="btn btn-gold">Leadership</a>
        <a href="/departments" class="btn btn-outline on-dark">Departments &amp; Zones</a>
      </div>
    </div>
  </div>
</section>

@endsection
