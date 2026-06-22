@extends('layouts.app')

@section('title', 'The Social Investment Fund | SIF Ghana')
@section('description', 'The Social Investment Fund (SIF) Ghana — a pro-poor institution mobilising resources and delivering inclusive economic and social development programmes that improve livelihoods across Ghana.')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<style>
/* =========================================================
   PREMIUM HOME — page-specific styles
   ========================================================= */

/* ---------- HERO ---------- */
.hero { min-height: 62vh; }
.hero-inner { padding: 88px 0 0; }

/* --- Slider --- */
.hero-bg { z-index: 0; }               /* establish stacking context */
.hero-bg::after { display: none; }    /* replaced by .hero-gradient-overlay */

.hero-gradient-overlay {
  position: absolute; inset: 0; z-index: 2; pointer-events: none;
  background:
    linear-gradient(180deg, rgba(7,59,42,0.45) 0%, rgba(7,59,42,0.18) 32%, rgba(7,59,42,0.97) 100%),
    linear-gradient(105deg, rgba(7,59,42,0.80) 0%, rgba(7,59,42,0.04) 62%);
}

.hero-slide {
  position: absolute; inset: 0; opacity: 0; z-index: 0;
  transition: opacity 1.5s ease;
}
.hero-slide.is-active { opacity: 1; z-index: 1; }

.hero-slide img {
  width: 100%; height: 100%; object-fit: cover;
  transform: scale(1.08);
  transition: transform 9s ease;
  will-change: transform;
}
.hero-slide.is-active img { transform: scale(1); }

/* --- Slider controls --- */
.slider-controls {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 0 22px; gap: 16px; position: relative; z-index: 3;
}
.slider-counter {
  font-family: var(--font-head); font-size: 12px; font-weight: 700;
  color: rgba(255,255,255,0.45); letter-spacing: 0.05em;
  display: flex; align-items: baseline; gap: 4px; min-width: 52px;
}
.slider-num { color: #fff; font-size: 18px; font-weight: 800; line-height: 1; }
.slider-dots {
  display: flex; align-items: center; gap: 7px;
}
.slider-dot {
  width: 8px; height: 8px; border-radius: 999px; padding: 0;
  background: rgba(255,255,255,0.28); border: none; cursor: pointer;
  transition: background 0.3s ease, width 0.4s cubic-bezier(0.4,0,0.2,1);
}
.slider-dot.is-active { background: var(--gold); width: 34px; }
.slider-dot:hover:not(.is-active) { background: rgba(255,255,255,0.55); }
.slider-arrows { display: flex; gap: 8px; min-width: 52px; justify-content: flex-end; }
.slider-arr {
  width: 38px; height: 38px; border-radius: 50%; padding: 0;
  background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.18);
  color: #fff; display: flex; align-items: center; justify-content: center;
  cursor: pointer; flex: none;
  transition: background 0.2s, border-color 0.2s, transform 0.15s;
  backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
}
.slider-arr:hover { background: rgba(255,255,255,0.18); border-color: rgba(255,255,255,0.42); }
.slider-arr:active { transform: scale(0.94); }
.slider-arr svg { width: 14px; height: 14px; flex: none; pointer-events: none; }

@media (max-width: 560px) {
  .slider-counter { display: none; }
  .slider-controls { justify-content: center; gap: 20px; }
  .slider-arrows { min-width: auto; }
}

.hero-badge {
  display: inline-flex; align-items: center; gap: 9px;
  background: rgba(214,167,44,0.14); border: 1px solid rgba(214,167,44,0.38);
  border-radius: 999px; padding: 6px 14px 6px 9px;
  font-family: var(--font-head); font-size: 11.5px; font-weight: 700;
  color: var(--gold-light); letter-spacing: 0.07em; text-transform: uppercase;
  margin-bottom: 12px; backdrop-filter: blur(6px);
}
.hero-badge-dot {
  width: 22px; height: 22px; border-radius: 50%;
  background: rgba(214,167,44,0.22); border: 1px solid rgba(214,167,44,0.45);
  display: flex; align-items: center; justify-content: center;
}
.hero-badge-dot::after {
  content: ""; width: 8px; height: 8px; border-radius: 50%; background: var(--gold);
}

.hero-content { width: 100%; min-width: 0; }
.hero h1 { font-size: clamp(30px, 4.2vw, 48px); margin-bottom: 12px; line-height: 1.06; overflow-wrap: anywhere; }
.hero h1 em { font-style: normal; color: var(--gold-light); }

.hero .lede { font-size: 16px; max-width: 500px; line-height: 1.5; }

.hero-divider {
  width: 56px; height: 3px; border-radius: 2px;
  background: linear-gradient(90deg, var(--gold), rgba(214,167,44,0.3));
  margin: 14px 0;
}

.hero-actions { margin-top: 18px; }
.hero-content { transition: opacity 0.24s ease, transform 0.24s ease; }
.hero-content.is-changing { opacity: 0; transform: translateY(6px); }
@media (max-width: 520px) {
  .hero-content,
  .hero .lede {
    width: min(100%, calc(100vw - 72px));
    max-width: min(100%, calc(100vw - 72px));
    min-width: 0;
  }
  .hero h1 {
    font-size: 30px;
    width: min(100%, calc(100vw - 72px));
    max-width: min(100%, calc(100vw - 72px));
  }
  .hero-badge {
    max-width: min(100%, calc(100vw - 72px));
  }
  .hero-badge span:last-child {
    min-width: 0;
    overflow-wrap: anywhere;
  }
  .hero-actions {
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
  }
  .hero-actions .btn {
    width: 100%;
    white-space: normal;
  }
}

.hero-scroll {
  position: absolute; bottom: 0; left: 0; right: 0;
  display: flex; justify-content: center; z-index: 3;
  pointer-events: none;
}
.hero-scroll-inner {
  display: flex; flex-direction: column; align-items: center; gap: 7px;
  padding: 14px 22px; color: rgba(255,255,255,0.5);
  font-size: 10.5px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
}
.hero-scroll-line {
  width: 1px; height: 48px;
  background: linear-gradient(180deg, rgba(255,255,255,0.5), transparent);
  animation: scrollLine 2s ease-in-out infinite;
}
@keyframes scrollLine {
  0%,100% { opacity: 0.5; transform: scaleY(1); transform-origin: top; }
  50% { opacity: 1; }
}

/* HERO PANEL — premium treatment */
.hero-panel {
  grid-template-columns: repeat(4,1fr);
  background: rgba(255,255,255,0.97);
  border-top: 3px solid var(--gold);
  box-shadow: 0 -24px 60px rgba(7,59,42,0.22);
}
.hero-stat { padding: 16px 20px; }
.hero-stat .num { font-size: clamp(22px, 2.1vw, 28px); }
.hero-stat-label-accent {
  display: flex; align-items: center; gap: 8px; margin-bottom: 10px;
}
.hero-stat-label-accent::before {
  content: ""; width: 18px; height: 2px;
  background: var(--gold); border-radius: 1px; flex: none;
}
.hero-stat-label-accent span {
  font-size: 10px; font-weight: 700; letter-spacing: 0.08em;
  text-transform: uppercase; color: var(--ink-soft);
}
@media (max-width: 880px) {
  .hero-panel { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 520px) {
  .hero-panel { grid-template-columns: 1fr; }
}

/* ---------- INSTITUTIONAL INTRO ---------- */
.intro-section {
  position: relative; overflow: hidden;
}
.intro-section::before {
  content: "SIF";
  position: absolute; right: -40px; top: 50%; transform: translateY(-50%);
  font-family: var(--font-head); font-size: 320px; font-weight: 800;
  color: rgba(7,59,42,0.025); line-height: 1; pointer-events: none; user-select: none;
  letter-spacing: -0.04em;
}

.intro-accent-line {
  display: block; width: 40px; height: 3px;
  background: var(--gold); border-radius: 2px; margin-bottom: 18px;
}

.intro-lead {
  font-size: 19px; font-weight: 600; color: var(--forest);
  line-height: 1.5; margin: 0 0 16px;
  font-family: var(--font-head);
}

.intro-stats-premium {
  display: flex; gap: 0; margin-top: 36px;
  border: 1px solid var(--line); border-radius: var(--radius-m); overflow: hidden;
}
.intro-stat-item {
  flex: 1; padding: 20px 22px;
  border-right: 1px solid var(--line);
  position: relative;
}
.intro-stat-item:last-child { border-right: none; }
.intro-stat-item::before {
  content: ""; position: absolute; top: 0; left: 0; right: 0; height: 3px;
  background: var(--gold); opacity: 0; transition: opacity 0.25s;
}
.intro-stat-item:hover::before { opacity: 1; }
.intro-stat-item strong {
  display: block; font-family: var(--font-head);
  font-size: 28px; font-weight: 800; color: var(--forest); line-height: 1;
}
.intro-stat-item span {
  font-size: 12px; color: var(--ink-soft); margin-top: 4px; display: block;
}

.intro-media-wrap {
  position: relative;
}
.imain-frame {
  border-radius: var(--radius-l); overflow: hidden;
  box-shadow: var(--shadow-l); aspect-ratio: 4/4.8; position: relative;
}
.imain-frame img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform 0.6s var(--ease);
}
.imain-frame:hover img { transform: scale(1.03); }

.intro-float-card {
  position: absolute; left: -32px; bottom: -24px;
  background: linear-gradient(rgba(7,59,42,0.78),rgba(7,59,42,0.78)), var(--sif-pattern) center/cover no-repeat, var(--forest); border-radius: var(--radius-m);
  box-shadow: var(--shadow-l); padding: 20px 24px; max-width: 230px;
}
.intro-float-card strong {
  display: block; font-family: var(--font-head); font-size: 13.5px;
  color: #fff; margin-bottom: 5px; line-height: 1.3;
}
.intro-float-card span {
  font-size: 11.5px; color: rgba(248,246,240,0.65);
}

.intro-float-badge {
  position: absolute; top: 28px; right: -20px;
  background: var(--gold); border-radius: var(--radius-m);
  padding: 14px 18px; box-shadow: var(--shadow-m);
  text-align: center;
}
.intro-float-badge strong {
  display: block; font-family: var(--font-head);
  font-size: 24px; font-weight: 800; color: var(--forest); line-height: 1;
}
.intro-float-badge span {
  font-size: 10.5px; font-weight: 700; color: rgba(7,59,42,0.7);
  text-transform: uppercase; letter-spacing: 0.05em;
}

@media (max-width:920px){
  .intro-section::before { display: none; }
  .intro-float-badge { right: 16px; top: 16px; }
  .intro-float-card { left: 16px; }
}

/* ---------- WHAT WE DO — numbered cards ---------- */
.services-premium-grid {
  display: grid; grid-template-columns: repeat(3,1fr); gap: 20px;
}
.service-card-p {
  background: #fff; border: 1px solid var(--line); border-radius: var(--radius-m);
  padding: 32px 28px; position: relative; overflow: hidden;
  transition: transform 0.22s var(--ease), box-shadow 0.22s var(--ease), border-color 0.22s;
  display: flex; flex-direction: column;
}
.service-card-p::before {
  content: ""; position: absolute; top: 0; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, var(--gold), var(--emerald));
  transform: scaleX(0); transform-origin: left;
  transition: transform 0.3s var(--ease);
}
.service-card-p:hover { transform: translateY(-5px); box-shadow: var(--shadow-m); border-color: transparent; }
.service-card-p:hover::before { transform: scaleX(1); }

.svc-num {
  font-family: var(--font-head); font-size: 42px; font-weight: 800;
  color: var(--line); line-height: 1; letter-spacing: -0.03em;
  margin-bottom: 16px; transition: color 0.22s;
}
.service-card-p:hover .svc-num { color: var(--emerald-light); }

.svc-icon {
  width: 48px; height: 48px; border-radius: 13px;
  background: var(--emerald-light); color: var(--emerald);
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 18px; transition: background 0.2s;
}
.svc-icon svg { width: 22px; height: 22px; }
.service-card-p:hover .svc-icon { background: var(--emerald); color: #fff; }

.service-card-p h3 { font-size: 17px; margin-bottom: 10px; }
.service-card-p p { color: var(--ink-soft); font-size: 14px; line-height: 1.65; flex: 1; }
.svc-link {
  display: inline-flex; align-items: center; gap: 5px;
  margin-top: 20px; font-size: 13px; font-weight: 700; color: var(--emerald);
}
.svc-link svg { width: 13px; height: 13px; transition: transform 0.18s var(--ease); }
.service-card-p:hover .svc-link svg { transform: translateX(3px); }

@media (max-width:900px){ .services-premium-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width:560px){ .services-premium-grid { grid-template-columns: 1fr; } }

/* ---------- FEATURED PROGRAMMES ---------- */
.programmes-header {
  display: flex; align-items: flex-end; justify-content: space-between;
  gap: 24px; margin-bottom: 32px; flex-wrap: wrap;
}
.programmes-chips {
  display: flex; flex-wrap: wrap; gap: 8px; flex: 1;
}
.project-card-featured {
  grid-column: span 3; display: grid; grid-template-columns: 1fr 1fr;
  border-radius: var(--radius-l); overflow: hidden; border: 1px solid var(--line);
  box-shadow: var(--shadow-s);
  transition: box-shadow 0.22s var(--ease);
  background: #fff; margin-bottom: 24px;
}
.project-card-featured:hover { box-shadow: var(--shadow-l); }
.pcf-image {
  position: relative; overflow: hidden; background: var(--cream-dim);
}
.pcf-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s var(--ease); }
.project-card-featured:hover .pcf-image img { transform: scale(1.04); }
.pcf-image-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to right, transparent 60%, rgba(7,59,42,0.08));
}
.pcf-flagship {
  position: absolute; top: 20px; left: 20px;
  background: var(--gold); color: var(--forest);
  font-family: var(--font-head); font-size: 10.5px; font-weight: 800;
  letter-spacing: 0.08em; text-transform: uppercase;
  padding: 5px 12px; border-radius: 999px;
  display: flex; align-items: center; gap: 6px;
}
.pcf-flagship::before {
  content: "★"; font-size: 9px;
}
.pcf-body {
  padding: 44px 40px; display: flex; flex-direction: column;
  justify-content: center; gap: 12px; background: #fff;
}
.pcf-meta {
  font-size: 11px; font-weight: 700; letter-spacing: 0.06em;
  text-transform: uppercase; color: var(--emerald);
  display: flex; align-items: center; gap: 10px;
}
.pcf-meta span { color: var(--ink-soft); font-weight: 500; }
.pcf-body h3 { font-size: clamp(20px, 2.2vw, 26px); line-height: 1.25; }
.pcf-body p { color: var(--ink-soft); font-size: 15px; line-height: 1.65; }
.pcf-details {
  display: grid; grid-template-columns: 1fr 1fr; gap: 10px 24px; margin-top: 8px;
}
.pcf-detail { display: flex; flex-direction: column; gap: 2px; }
.pcf-detail dt { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink-soft); }
.pcf-detail dd { font-size: 13.5px; font-weight: 600; color: var(--forest); margin: 0; }
.pcf-link {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 12px 24px; background: var(--forest); color: #fff;
  border-radius: 999px; font-family: var(--font-head);
  font-weight: 700; font-size: 14px; margin-top: 8px;
  width: fit-content; transition: background 0.2s, transform 0.2s;
}
.pcf-link svg { width: 14px; height: 14px; }
.pcf-link:hover { background: var(--emerald); transform: translateX(3px); }
.pcf-regions {
  display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px;
}
.pcf-regions span {
  font-size: 11px; font-weight: 600; background: var(--cream);
  color: var(--ink-soft); padding: 4px 10px; border-radius: 999px;
}

.projects-sub-grid {
  display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;
}

@media (max-width: 920px) {
  .project-card-featured { grid-template-columns: 1fr; }
  .pcf-image { min-height: 280px; }
  .pcf-body { padding: 28px 24px; }
  .projects-sub-grid { grid-template-columns: 1fr; }
}

/* ---------- IMPACT STATS — dramatic ---------- */
.impact-section-premium {
  background: linear-gradient(rgba(7,59,42,0.78),rgba(7,59,42,0.78)), var(--sif-pattern) center/cover no-repeat, var(--forest);
}

.impact-section-head {
  text-align: center; padding-bottom: 56px;
}
.impact-section-head h2 { color: #fff; font-size: clamp(28px, 3.6vw, 44px); }
.impact-section-head p { color: rgba(248,246,240,0.65); font-size: 16px; margin-top: 12px; }

.impact-primary {
  display: grid; grid-template-columns: repeat(3, 1fr);
  border: 1px solid rgba(248,246,240,0.1); border-radius: var(--radius-l) var(--radius-l) 0 0;
  overflow: hidden;
}
.impact-primary-cell {
  padding: 52px 36px; text-align: center;
  border-right: 1px solid rgba(248,246,240,0.1);
  position: relative; overflow: hidden;
}
.impact-primary-cell:last-child { border-right: none; }
.impact-primary-cell::before {
  content: ""; position: absolute; top: 0; left: 50%; transform: translateX(-50%);
  width: 40px; height: 3px; background: var(--gold); border-radius: 0 0 2px 2px;
}
.impact-primary-num {
  font-family: var(--font-head); font-weight: 800;
  font-size: clamp(44px, 5.5vw, 72px); line-height: 1;
  color: #fff; letter-spacing: -0.03em;
}
.impact-primary-num .prefix { font-size: 0.48em; font-weight: 700; vertical-align: top; margin-top: 0.2em; display: inline-block; }
.impact-primary-num .unit { font-size: 0.45em; color: var(--gold); font-weight: 800; }
.impact-primary-label { font-size: 13px; color: rgba(248,246,240,0.6); margin-top: 10px; line-height: 1.4; }
.impact-primary-note { font-size: 11px; color: rgba(248,246,240,0.35); margin-top: 6px; }

.impact-secondary {
  display: grid; grid-template-columns: repeat(3, 1fr);
  border: 1px solid rgba(248,246,240,0.1); border-top: none;
  border-radius: 0 0 var(--radius-l) var(--radius-l); overflow: hidden;
  background: rgba(255,255,255,0.04);
}
.impact-secondary-cell {
  padding: 30px 36px; display: flex; align-items: center; gap: 20px;
  border-right: 1px solid rgba(248,246,240,0.1);
}
.impact-secondary-cell:last-child { border-right: none; }
.impact-sec-num {
  font-family: var(--font-head); font-weight: 800;
  font-size: clamp(24px, 2.6vw, 34px); color: #fff; white-space: nowrap; flex: none;
}
.impact-sec-num .unit { font-size: 0.55em; color: var(--gold); }
.impact-sec-label { font-size: 12px; color: rgba(248,246,240,0.55); line-height: 1.45; }

@media (max-width: 980px) {
  .impact-primary { grid-template-columns: 1fr 1fr; }
  .impact-primary-cell:nth-child(2) { border-right: none; }
  .impact-primary-cell:last-child { grid-column: 1/-1; }
  .impact-secondary { grid-template-columns: 1fr 1fr; }
  .impact-secondary-cell:nth-child(2) { border-right: none; }
  .impact-secondary-cell:last-child { grid-column: 1/-1; }
}
@media (max-width: 560px) {
  .impact-primary { grid-template-columns: 1fr 1fr; }
  .impact-primary-cell { padding: 32px 20px; }
  .impact-secondary-cell { padding: 22px 20px; }
}

/* ---------- STORY CARD ---------- */
.story-card-premium {
  display: grid; grid-template-columns: 1fr 1fr;
  border-radius: var(--radius-l); overflow: hidden; box-shadow: var(--shadow-l);
}
.story-img-premium {
  position: relative; overflow: hidden; min-height: 480px;
}
.story-img-premium img {
  width: 100%; height: 100%; object-fit: cover;
  position: absolute; inset: 0; transition: transform 0.6s var(--ease);
}
.story-card-premium:hover .story-img-premium img { transform: scale(1.04); }
.story-img-premium::after {
  content: ""; position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(7,59,42,0.3), transparent);
}
.story-body-premium {
  background: linear-gradient(rgba(7,59,42,0.78),rgba(7,59,42,0.78)), var(--sif-pattern) center/cover no-repeat, var(--forest); color: #fff;
  padding: 56px 48px; display: flex; flex-direction: column; justify-content: center; gap: 20px;
}
.story-tag {
  display: inline-flex; align-items: center; gap: 7px;
  background: rgba(214,167,44,0.12); border: 1px solid rgba(214,167,44,0.28);
  border-radius: 999px; padding: 6px 14px;
  font-size: 10.5px; font-weight: 700; color: var(--gold-light);
  letter-spacing: 0.06em; text-transform: uppercase; width: fit-content;
}
.story-tag::before { content: ""; width: 5px; height: 5px; border-radius: 50%; background: var(--gold); }
.story-quote-mark {
  font-family: Georgia, serif; font-size: 72px; line-height: 0.5;
  color: var(--gold); opacity: 0.5; margin-bottom: -8px;
}
.story-quote-text {
  font-family: var(--font-head); font-size: clamp(17px, 1.8vw, 22px);
  line-height: 1.5; font-weight: 600; color: #fff;
}
.story-attribution {
  border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;
}
.story-attribution strong { display: block; font-size: 13.5px; color: #fff; margin-bottom: 3px; }
.story-attribution span { font-size: 12px; color: rgba(248,246,240,0.55); }
.story-outcome-pill {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(8,127,91,0.18); border: 1px solid rgba(8,127,91,0.35);
  border-radius: 999px; padding: 9px 16px;
  font-size: 12.5px; font-weight: 600; color: rgba(230,243,238,0.9);
  width: fit-content;
}
.story-outcome-pill svg { width: 13px; height: 13px; color: #5CE0A8; flex: none; }

@media (max-width: 880px) {
  .story-card-premium { grid-template-columns: 1fr; }
  .story-img-premium { min-height: 280px; }
  .story-body-premium { padding: 36px 26px; }
}

/* ---------- NEWS — editorial ---------- */
.news-editorial-header {
  display: flex; align-items: flex-end; justify-content: space-between;
  gap: 20px; margin-bottom: 36px; flex-wrap: wrap;
}
.news-editorial-header .section-head { margin-bottom: 0; }

.news-editorial-grid {
  display: grid; grid-template-columns: 1.4fr 1fr; gap: 24px;
}
.news-feature-card {
  background: #fff; border-radius: var(--radius-l);
  overflow: hidden; border: 1px solid var(--line);
  transition: box-shadow 0.22s; display: flex; flex-direction: column;
}
.news-feature-card:hover { box-shadow: var(--shadow-m); }
.nfc-image {
  position: relative; aspect-ratio: 16/10; overflow: hidden; background: var(--cream-dim);
}
.nfc-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s var(--ease); }
.news-feature-card:hover .nfc-image img { transform: scale(1.04); }
.nfc-cat {
  position: absolute; top: 16px; left: 16px;
  background: rgba(255,255,255,0.92); backdrop-filter: blur(4px);
  color: var(--forest); font-size: 10.5px; font-weight: 700;
  padding: 5px 13px; border-radius: 999px;
  text-transform: uppercase; letter-spacing: 0.05em;
}
.nfc-body { padding: 28px; display: flex; flex-direction: column; gap: 10px; flex: 1; }
.nfc-date { font-size: 11px; color: var(--ink-soft); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
.nfc-body h3 { font-size: 22px; line-height: 1.3; }
.nfc-body p { color: var(--ink-soft); font-size: 14.5px; line-height: 1.6; }
.nfc-read {
  display: inline-flex; align-items: center; gap: 6px; margin-top: auto;
  font-size: 13px; font-weight: 700; color: var(--emerald);
}
.nfc-read svg { width: 13px; height: 13px; transition: transform 0.18s var(--ease); }
.news-feature-card:hover .nfc-read svg { transform: translateX(3px); }

.news-side-stack { display: flex; flex-direction: column; gap: 16px; }
.news-side-card {
  background: #fff; border-radius: var(--radius-m);
  overflow: hidden; border: 1px solid var(--line);
  display: flex; gap: 0; transition: box-shadow 0.2s, transform 0.2s;
  flex: 1;
}
.news-side-card:hover { box-shadow: var(--shadow-m); transform: translateX(3px); }
.nsc-image {
  width: 120px; flex: none; position: relative; overflow: hidden; background: var(--cream-dim);
}
.nsc-image img { width: 100%; height: 100%; object-fit: cover; }
.nsc-body { padding: 16px 18px; display: flex; flex-direction: column; gap: 6px; flex: 1; }
.nsc-cat { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--emerald); }
.nsc-body h4 { font-size: 14px; line-height: 1.35; font-family: var(--font-head); font-weight: 700; color: var(--forest); }
.nsc-date { font-size: 11px; color: var(--ink-soft); margin-top: auto; }

@media (max-width: 900px) {
  .news-editorial-grid { grid-template-columns: 1fr; }
  .news-side-card { flex-direction: column; }
  .nsc-image { width: 100%; height: 160px; }
}

/* ---------- PARTNERS premium ---------- */
.partners-premium {
  padding: 48px 0; background: var(--white);
  border-top: 1px solid var(--line); border-bottom: 1px solid var(--line);
}
.partners-premium-inner {
  display: flex; flex-direction: column; align-items: center; gap: 24px;
}
.partners-kicker {
  font-family: var(--font-head); font-size: 11px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 0.09em; color: var(--ink-soft);
  display: flex; align-items: center; gap: 14px;
}
.partners-kicker::before,.partners-kicker::after {
  content: ""; height: 1px; width: 40px; background: var(--line);
}

/* ---------- ACCOUNTABILITY BANNER ---------- */
.accountability-premium {
  background: linear-gradient(rgba(7,59,42,0.78),rgba(7,59,42,0.78)), var(--sif-pattern) center/cover no-repeat, var(--forest); border-radius: var(--radius-l);
  overflow: hidden; position: relative;
}
.accountability-premium::before {
  content: ""; position: absolute; top: 0; left: 0; bottom: 0; width: 4px;
  background: linear-gradient(180deg, var(--gold), var(--emerald));
}
.acc-inner {
  padding: 56px 52px; display: grid;
  grid-template-columns: 1.1fr 1fr; gap: 48px; align-items: center;
  position: relative; z-index: 1;
}
.acc-eyebrow {
  display: inline-flex; align-items: center; gap: 8px;
  font-family: var(--font-head); font-size: 11px; font-weight: 700;
  letter-spacing: 0.08em; text-transform: uppercase; color: var(--gold-light);
  margin-bottom: 16px;
}
.acc-eyebrow::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: var(--gold); }
.acc-inner h2 { color: #fff; font-size: clamp(24px, 3vw, 36px); margin-bottom: 14px; }
.acc-inner p { color: rgba(248,246,240,0.7); font-size: 15.5px; line-height: 1.65; max-width: 440px; }
.acc-assurances {
  display: flex; flex-direction: column; gap: 12px; margin-top: 28px;
}
.acc-assurance {
  display: flex; align-items: center; gap: 10px;
  font-size: 13px; font-weight: 600; color: rgba(248,246,240,0.8);
}
.acc-assurance svg { width: 14px; height: 14px; color: var(--gold); flex: none; }

.acc-actions {
  display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
}
.acc-action {
  background: rgba(255,255,255,0.06); border: 1px solid rgba(248,246,240,0.12);
  border-radius: var(--radius-m); padding: 20px 18px;
  display: flex; flex-direction: column; gap: 6px;
  transition: background 0.2s, border-color 0.2s, transform 0.2s;
}
.acc-action:hover { background: rgba(255,255,255,0.11); border-color: rgba(214,167,44,0.4); transform: translateY(-2px); }
.acc-action svg { width: 20px; height: 20px; color: var(--gold); margin-bottom: 4px; }
.acc-action strong { font-family: var(--font-head); font-size: 13.5px; color: #fff; }
.acc-action span { font-size: 11.5px; color: rgba(248,246,240,0.55); }

@media (max-width: 880px) {
  .acc-inner { grid-template-columns: 1fr; padding: 36px 28px; gap: 32px; }
  .acc-actions { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 480px) {
  .acc-actions { grid-template-columns: 1fr; }
}

/* ---------- NEWSLETTER premium ---------- */
.nl-premium-wrap {
  max-width: 640px; margin: 0 auto; text-align: center;
}
.nl-premium-wrap h3 {
  font-size: clamp(22px, 3vw, 34px); line-height: 1.2; margin-bottom: 10px;
}
.nl-premium-wrap p { color: var(--ink-soft); font-size: 15.5px; }
.nl-pill-form {
  display: flex; gap: 0; margin-top: 32px;
  background: #fff; border-radius: 999px; padding: 6px 6px 6px 22px;
  box-shadow: 0 4px 24px rgba(7,59,42,0.10); border: 1.5px solid var(--line);
  transition: border-color 0.2s, box-shadow 0.2s;
}
.nl-pill-form:focus-within { border-color: var(--emerald); box-shadow: 0 0 0 3px var(--emerald-light); }
.nl-pill-form input {
  flex: 1; border: none; outline: none; background: transparent;
  font-size: 15px; padding: 8px 0;
}
.nl-pill-form button {
  background: var(--gold); color: var(--forest); border: none;
  padding: 13px 26px; border-radius: 999px; cursor: pointer;
  font-family: var(--font-head); font-size: 14px; font-weight: 700;
  white-space: nowrap; transition: background 0.2s, transform 0.15s;
  flex: none;
}
.nl-pill-form button:hover { background: #C2962A; transform: scale(1.02); }
.nl-privacy-note {
  margin-top: 14px; font-size: 12px; color: var(--ink-soft);
  display: flex; align-items: center; justify-content: center; gap: 6px;
}
.nl-privacy-note svg { width: 12px; height: 12px; }
</style>
@endpush

@section('content')

{{-- ===================== 1. HERO ===================== --}}
<section class="hero" id="top">

  {{-- Slides --}}
  <div class="hero-bg">
    <div class="hero-slide is-active">
      <img src="https://sifinghana.org/backend/images/uploads/_ENN6117.jpg1742893405.jpg" alt="SIF project site — community and contractors" data-fallback loading="eager">
    </div>
    <div class="hero-slide">
      <img src="/images/gwyesco-launch.jpg" alt="GWYESCO programme launch" data-fallback loading="lazy">
    </div>
    <div class="hero-slide">
      <img src="https://sifinghana.org/backend/images/uploads/afigya%20kwabre%20irdp%202.jpg1742825153.jpg" alt="IRDP II rural infrastructure project" data-fallback loading="lazy">
    </div>
    <div class="hero-slide">
      <img src="https://sifinghana.org/backend/images/uploads/PSDPEP%20scholar.jpg%20web.jpg1743597962.jpg" alt="PSDPEP skills development scholar" data-fallback loading="lazy">
    </div>
    <div class="hero-gradient-overlay"></div>
  </div>

  <div class="hero-inner">
    <div class="container">
      <div class="hero-content reveal">

        <div class="hero-badge" id="heroBadge">
          <span class="hero-badge-dot"></span>
          <span id="heroBadgeText">Est. 1998 &mdash; An Agency of the Government of Ghana</span>
        </div>

        <h1 id="heroTitle">Investing in People.<br>Strengthening Communities.<br><em>Transforming Ghana.</em></h1>

        <div class="hero-divider"></div>

        <p class="lede" id="heroLede">The Social Investment Fund mobilises resources and delivers inclusive economic and social development programmes that improve livelihoods across all sixteen regions of Ghana.</p>

        <div class="hero-actions">
          <a href="#impact" class="btn btn-gold" id="heroPrimaryCta">
            <span id="heroPrimaryText">Explore Our Impact</span>
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
          </a>
          <a href="/projects" class="btn btn-outline on-dark" id="heroSecondaryCta">View Current Projects</a>
        </div>

      </div>

      {{-- Slider controls --}}
      <div class="slider-controls" aria-label="Hero slideshow controls">
        <div class="slider-counter" aria-live="polite" aria-atomic="true">
          <span class="slider-num" id="slideCurrentNum">01</span>
          <span>/</span>
          <span>04</span>
        </div>
        <div class="slider-dots" role="tablist" aria-label="Select slide">
          <button class="slider-dot is-active" role="tab" aria-label="Slide 1" aria-selected="true"  data-goto="0"></button>
          <button class="slider-dot"            role="tab" aria-label="Slide 2" aria-selected="false" data-goto="1"></button>
          <button class="slider-dot"            role="tab" aria-label="Slide 3" aria-selected="false" data-goto="2"></button>
          <button class="slider-dot"            role="tab" aria-label="Slide 4" aria-selected="false" data-goto="3"></button>
        </div>
        <div class="slider-arrows">
          <button class="slider-arr" id="sliderPrev" aria-label="Previous slide">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M15 18l-6-6 6-6"/></svg>
          </button>
          <button class="slider-arr" id="sliderNext" aria-label="Next slide">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M9 18l6-6-6-6"/></svg>
          </button>
        </div>
      </div>

      <div class="hero-panel reveal-stagger">
        <div class="hero-stat reveal" style="--i:0">
          <div class="hero-stat-label-accent"><span>Years Active</span></div>
          <div class="num"><span data-count="27">0</span><span class="unit">+</span></div>
          <div class="label">Years mobilising resources for Ghana&rsquo;s poor</div>
        </div>
        <div class="hero-stat reveal" style="--i:1">
          <div class="hero-stat-label-accent"><span>Infrastructure</span></div>
          <div class="num"><span data-count="1629">0</span><span class="unit">+</span></div>
          <div class="label">Social &amp; economic projects delivered since 1998</div>
        </div>
        <div class="hero-stat reveal" style="--i:2">
          <div class="hero-stat-label-accent"><span>Beneficiaries</span></div>
          <div class="num"><span data-count="1.6">0</span><span class="unit">M+</span></div>
          <div class="label">Ghanaians reached nationwide</div>
        </div>
        <div class="hero-stat reveal" style="--i:3">
          <div class="hero-stat-label-accent"><span>Coverage</span></div>
          <div class="num"><span data-count="16">0</span></div>
          <div class="label">Regions covered — 4 operational zones</div>
        </div>
      </div>
    </div>
  </div>

  <div class="hero-scroll" aria-hidden="true">
    <div class="hero-scroll-inner">
      <div class="hero-scroll-line"></div>
    </div>
  </div>
</section>


{{-- ===================== 2. INSTITUTIONAL INTRO ===================== --}}
<section class="sec bg-cream intro-section" id="about-intro">
  <div class="container">
    <div class="intro-grid">
      <div class="intro-copy reveal">
        <span class="intro-accent-line"></span>
        <span class="eyebrow">About the Social Investment Fund</span>
        <p class="intro-lead" style="margin-top:16px;">A pro-poor institution, built to last — and still delivering, almost three decades on.</p>
        <p style="color:var(--ink-soft);font-size:16px;line-height:1.75;margin-top:0;">Incorporated in 1998 under Ghana&rsquo;s Companies Code, SIF was formed by the Government of Ghana together with the African Development Bank and the United Nations Development Programme — a fast, dependable channel for resourcing the country&rsquo;s most underserved communities.</p>
        <p style="color:var(--ink-soft);font-size:16px;line-height:1.75;margin-top:14px;">SIF&rsquo;s mission is to mobilise internal and external resources, provide consultancy services, and improve access to economic and social infrastructure — for sustainable poverty reduction, built on the principles of a green economy.</p>

        <div class="intro-stats-premium" style="margin-top:36px;">
          <div class="intro-stat-item">
            <strong>1998</strong>
            <span>Year established</span>
          </div>
          <div class="intro-stat-item">
            <strong>4</strong>
            <span>Operational zones</span>
          </div>
          <div class="intro-stat-item">
            <strong>6</strong>
            <span>Flagship programmes</span>
          </div>
        </div>

        <div style="margin-top:32px;display:flex;gap:14px;flex-wrap:wrap;">
          <a href="/about" class="btn btn-primary">Read Our Full Story
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
          </a>
          <a href="https://sifinghana.org/images/DRAFT%20Social%20Investment%20Fund%20Corporate%20Profile.pdf" target="_blank" rel="noopener" class="btn-ghost">
            Download Corporate Profile
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
          </a>
        </div>
      </div>

      <div class="intro-media-wrap reveal" style="transition-delay:120ms;">
        <div class="imain-frame">
          <img src="https://sifinghana.org/backend/images/uploads/WhatsApp%20Image%202026-01-21%20at%204.15.48%20PM.jpeg1769013143.jpeg" alt="SIF project handover with community and contractor representatives" data-fallback loading="lazy">
        </div>
        <div class="intro-float-card">
          <strong>Pro-Poor Development Institution</strong>
          <span>Serving communities across all sixteen regions of Ghana</span>
        </div>
        <div class="intro-float-badge">
          <strong>27<sup style="font-size:0.5em;vertical-align:super;">+</sup></strong>
          <span>Years of<br>Impact</span>
        </div>
      </div>
    </div>
  </div>
</section>


{{-- ===================== 3. WHAT WE DO ===================== --}}
<section class="sec bg-white" id="what-we-do">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">What We Do</span>
      <h2>Six areas of deep institutional competence.</h2>
      <p>From first feasibility study to final evaluation report — SIF brings end-to-end capability to every engagement, for government, development partners and the private sector.</p>
    </div>
    <div class="services-premium-grid">

      <div class="service-card-p reveal" style="--i:0">
        <div class="svc-num">01</div>
        <div class="svc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/></svg></div>
        <h3>Social &amp; Economic Infrastructure</h3>
        <p>Designing and delivering the schools, clinics, water systems and market infrastructure that underserved communities need most.</p>
        <a href="/about#objectives" class="svc-link">Learn more <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></a>
      </div>

      <div class="service-card-p reveal" style="--i:1">
        <div class="svc-num">02</div>
        <div class="svc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="8" r="3.4"/><path d="M5.5 12.5h13M5.5 16h13M5.5 19.5h13"/></svg></div>
        <h3>Microfinance &amp; Financial Inclusion</h3>
        <p>Putting low-interest credit and financial services into the hands of women- and youth-led micro, small and medium enterprises.</p>
        <a href="/about#objectives" class="svc-link">Learn more <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></a>
      </div>

      <div class="service-card-p reveal" style="--i:2">
        <div class="svc-num">03</div>
        <div class="svc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 6.5 12 3l8 3.5-8 3.5-8-3.5Z"/><path d="M4 6.5V15c0 1.5 3.6 4 8 4s8-2.5 8-4V6.5"/></svg></div>
        <h3>Community Capacity Building</h3>
        <p>Equipping institutions, artisans and local leaders with the skills to sustain results long after a project closes.</p>
        <a href="/about#objectives" class="svc-link">Learn more <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></a>
      </div>

      <div class="service-card-p reveal" style="--i:3">
        <div class="svc-num">04</div>
        <div class="svc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M9 3h6l-1 6h4l-9 12 2-9H7z"/></svg></div>
        <h3>Project Management</h3>
        <p>End-to-end design, development and delivery of projects for MMDAs, development partners and DFIs — on time, on budget.</p>
        <a href="/about#objectives" class="svc-link">Learn more <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></a>
      </div>

      <div class="service-card-p reveal" style="--i:4">
        <div class="svc-num">05</div>
        <div class="svc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 19V10M10 19V5M16 19v-7M21 19H3"/></svg></div>
        <h3>Monitoring &amp; Evaluation</h3>
        <p>Disciplined delivery tracked against agreed results — proving impact on the ground, not just on paper.</p>
        <a href="/about#objectives" class="svc-link">Learn more <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></a>
      </div>

      <div class="service-card-p reveal" style="--i:5">
        <div class="svc-num">06</div>
        <div class="svc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="3.4"/><path d="M2 12s3.6-6.5 10-6.5S22 12 22 12s-3.6 6.5-10 6.5S2 12 2 12Z"/></svg></div>
        <h3>Development Consultancy</h3>
        <p>Research, innovation and advisory services grounded in evidence, data and local context — for public and private organisations alike.</p>
        <a href="/about#objectives" class="svc-link">Learn more <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></a>
      </div>

    </div>
  </div>
</section>


{{-- ===================== 4. FEATURED PROGRAMMES ===================== --}}
<section class="sec bg-dim" id="programmes">
  <div class="container">

    <div class="programmes-header">
      <div class="section-head reveal" style="margin-bottom:0;">
        <span class="eyebrow">Featured Programmes</span>
        <h2>From poverty reduction to post-pandemic recovery.</h2>
        <p>Six flagship programmes spanning almost three decades — funded by government and the continent&rsquo;s leading development institutions.</p>
      </div>
    </div>

    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:36px;" class="reveal">
      <a class="chip is-active" href="/projects">All Projects</a>
      <a class="chip" href="/projects?status=ongoing">Active</a>
      <a class="chip" href="/projects?status=completed">Completed</a>
      <a class="chip" href="/projects?category=Infrastructure">Infrastructure</a>
      <a class="chip" href="/projects?category=Employment">Employment</a>
      <a class="chip" href="/projects?category=Women%20and%20Youth">Women &amp; Youth</a>
    </div>

    {{-- Flagship card --}}
    <a href="/projects/gwyesco" class="project-card-featured reveal" style="text-decoration:none;display:grid;">
      <div class="pcf-image">
        <img src="/images/gwyesco-launch.jpg" alt="GWYESCO programme launch" data-fallback loading="lazy">
        <div class="pcf-image-overlay"></div>
        <div class="pcf-flagship">Flagship Programme</div>
        <span class="status new" style="top:auto;bottom:20px;left:20px;">New &middot; Launched 2026</span>
      </div>
      <div class="pcf-body">
        <div class="pcf-meta">GWYESCO <span>&middot; 2026 – 2028</span></div>
        <h3>Ghana Women &amp; Youth Employment and Social Cohesion Programme</h3>
        <p>Ghana&rsquo;s first AfDB results-based financing operation — training 30,000+ women and young people in STEM, digital and creative skills, expanding MSME finance access, and rebuilding social cohesion in northern Ghana.</p>
        <div class="pcf-regions">
          <span>Northern</span><span>Upper East</span><span>Upper West</span><span>North East</span><span>Nationwide</span>
        </div>
        <dl class="pcf-details">
          <div class="pcf-detail"><dt>Funder</dt><dd>African Development Bank (AfDB)</dd></div>
          <div class="pcf-detail"><dt>Value</dt><dd>US$71.25M grant</dd></div>
          <div class="pcf-detail"><dt>Timeline</dt><dd>2026 – 2028</dd></div>
          <div class="pcf-detail"><dt>Beneficiaries</dt><dd>30,000+ women &amp; youth</dd></div>
        </dl>
        <div class="pcf-link">
          View Programme
          <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
        </div>
      </div>
    </a>

    {{-- Sub-grid for other projects --}}
    <div class="projects-sub-grid">
      <article class="project-card reveal" style="--i:0">
        <div class="pimg">
          <span class="status ongoing">Ongoing &middot; 2023 – 2027</span>
          <img src="https://sifinghana.org/backend/images/uploads/PSDPEP%20scholar.jpg%20web.jpg1743597962.jpg" alt="PSDPEP project activities" data-fallback loading="lazy">
        </div>
        <div class="pbody">
          <span class="pmeta">PSDPEP &middot; 2023 – 2027</span>
          <h3>Post-COVID-19 Skills Development &amp; Productivity Enhancement</h3>
          <p>Rehabilitating health and education infrastructure and putting low-interest microcredit into the hands of women- and youth-led MSMEs.</p>
          <div class="pregions"><span>Greater Accra</span><span>Northern</span><span>Western</span></div>
          <div class="pfund">Funded by <strong>AfDB</strong> (US$28.5M) + <strong>GoG</strong> (US$2.8M)</div>
          <a href="/projects/psdpep" class="pfoot-link">View Programme <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></a>
        </div>
      </article>
      <article class="project-card reveal" style="--i:1">
        <div class="pimg">
          <span class="status ongoing">Ongoing</span>
          <img src="https://sifinghana.org/backend/images/uploads/afigya%20kwabre%20irdp%202.jpg1742825153.jpg" alt="IRDP II project site" data-fallback loading="lazy">
        </div>
        <div class="pbody">
          <span class="pmeta">IRDP II</span>
          <h3>Integrated Rural Development Project, Phase II</h3>
          <p>Extending socio-economic infrastructure and livelihood support to underserved rural districts nationwide.</p>
          <div class="pregions"><span>Ashanti</span><span>Nationwide rural</span></div>
          <div class="pfund">Funded by <strong>OFID</strong></div>
          <a href="/projects/irdp2" class="pfoot-link">View Programme <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></a>
        </div>
      </article>
    </div>

    <div style="text-align:center;margin-top:40px;">
      <a href="/projects" class="btn btn-outline">View All Six Programmes
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
      </a>
    </div>
  </div>
</section>


{{-- ===================== 5. PROJECT MAP ===================== --}}
<section class="sec bg-white" id="map">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Where We Work</span>
      <h2>Four zones. Sixteen regions. One mission.</h2>
      <p>SIF&rsquo;s Fund Management Unit in Accra coordinates delivery nationwide. Explore approximate programme locations below — markers are schematic, placed at the nearest major town for each documented operating area, not exact site coordinates.</p>
    </div>
    <div class="map-wrap reveal">
      <div id="siteMap"></div>
      <div class="map-side">
        <div class="map-side-head"><h4>Programme Locations</h4></div>
        <div class="map-zone-legend" id="zoneLegend"></div>
        <div class="map-list" id="mapList"></div>
      </div>
    </div>
  </div>
</section>


{{-- ===================== 6. IMPACT STATISTICS ===================== --}}
<section class="sec impact-section-premium" id="impact">
  <div class="container">
    <div class="impact-section-head reveal">
      <span class="eyebrow on-dark">Our Impact</span>
      <h2>Numbers that represent communities, not spreadsheets.</h2>
      <p>Verified institutional totals since incorporation in 1998.</p>
    </div>

    <div class="impact-primary reveal">
      <div class="impact-primary-cell">
        <div class="impact-primary-num">
          <span class="prefix">US$</span><span data-count="83.5">0</span><span class="unit">M+</span>
        </div>
        <div class="impact-primary-label">Mobilised from development partners since 1998</div>
        <div class="impact-primary-note">AfDB &middot; OFID &middot; BADEA &middot; GoG &middot; UNDP</div>
      </div>
      <div class="impact-primary-cell">
        <div class="impact-primary-num">
          <span data-count="1629">0</span><span class="unit">+</span>
        </div>
        <div class="impact-primary-label">Social &amp; economic infrastructure projects delivered</div>
        <div class="impact-primary-note">Verified, across all 16 regions</div>
      </div>
      <div class="impact-primary-cell">
        <div class="impact-primary-num">
          <span data-count="1.6">0</span><span class="unit">M+</span>
        </div>
        <div class="impact-primary-label">Ghanaians reached — approximately 10% of the national poor</div>
        <div class="impact-primary-note">Beneficiaries supported nationwide</div>
      </div>
    </div>

    <div class="impact-secondary reveal">
      <div class="impact-secondary-cell">
        <div class="impact-sec-num"><span data-count="16">0</span></div>
        <div class="impact-sec-label">Regions covered across 4 operational zones</div>
      </div>
      <div class="impact-secondary-cell">
        <div class="impact-sec-num"><span data-count="5000">0</span><span class="unit">+</span></div>
        <div class="impact-sec-label">Artisans employed on project sites</div>
      </div>
      <div class="impact-secondary-cell">
        <div class="impact-sec-num"><span data-count="5">0</span></div>
        <div class="impact-sec-label">Government &amp; development partner relationships</div>
      </div>
    </div>
  </div>
</section>


{{-- ===================== 7. IMPACT STORY ===================== --}}
<section class="sec bg-dim" id="story">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Development Through Real Human Impact</span>
      <h2>What SIF&rsquo;s programmes are designed to deliver.</h2>
    </div>
    <div class="story-card-premium reveal">
      <div class="story-img-premium">
        <img src="https://sifinghana.org/backend/images/uploads/PSDPEP%20scholar.jpg%20web.jpg1743597962.jpg" alt="PSDPEP scholar supported through the programme" data-fallback loading="lazy">
      </div>
      <div class="story-body-premium">
        <span class="story-tag">PSDPEP Skills Track</span>
        <div>
          <div class="story-quote-mark">&ldquo;</div>
          <p class="story-quote-text">Access to skills training and a small amount of working capital is often the difference between an idea and a livelihood.</p>
        </div>
        <div class="story-attribution">
          <strong>Illustrative composite, representative of PSDPEP&rsquo;s intended beneficiaries</strong>
          <span>Skills Development Track &middot; PSDPEP Programme</span>
        </div>
        <div class="story-outcome-pill">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m5 12 5 5L20 7"/></svg>
          Aligned with PSDPEP&rsquo;s US$4M MSME microcredit facility
        </div>
        <div>
          <a href="/projects/psdpep" class="btn btn-outline on-dark" style="width:fit-content;">Read About PSDPEP</a>
        </div>
      </div>
    </div>
  </div>
</section>


{{-- ===================== 8. NEWS & MEDIA ===================== --}}
<section class="sec bg-white" id="news">
  <div class="container">
    <div class="news-editorial-header">
      <div class="section-head reveal" style="margin-bottom:0;">
        <span class="eyebrow">News &amp; Media</span>
        <h2>From the field.</h2>
        <p>The latest from SIF&rsquo;s projects, missions and partnerships across Ghana.</p>
      </div>
      <a href="/news" class="btn btn-outline reveal" style="flex:none;align-self:flex-end;">
        View All News
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
      </a>
    </div>

    <div class="news-editorial-grid">
      <a class="news-feature-card reveal" href="https://www.myjoyonline.com/ghana-launches-landmark-women-and-youth-employment-programme-to-create-over-30000-jobs/" target="_blank" rel="noopener">
        <div class="nfc-image">
          <img src="/images/gwyesco-launch.jpg" alt="" data-fallback loading="lazy">
          <span class="nfc-cat">Programme Update</span>
        </div>
        <div class="nfc-body">
          <span class="nfc-date">June 2026</span>
          <h3>Ghana launches GWYESCO to create 30,000+ jobs for women and youth</h3>
          <p>A US$71.25M AfDB grant backs Ghana&rsquo;s first results-based financing programme, implemented by SIF in partnership with the Ministry of Finance.</p>
          <span class="nfc-read">Read the story <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></span>
        </div>
      </a>

      <div class="news-side-stack">
        <a class="news-side-card reveal" href="https://sifinghana.org/page.php?id=3278" target="_blank" rel="noopener">
          <div class="nsc-image">
            <img src="https://sifinghana.org/backend/images/uploads/20260119_133435-2.jpg.jpeg1769166677.jpeg" alt="" data-fallback loading="lazy">
          </div>
          <div class="nsc-body">
            <span class="nsc-cat">Partnerships</span>
            <h4>BADEA appraisal mission meets with SIF &amp; Ministry of Finance</h4>
            <span class="nsc-date">January 2026</span>
          </div>
        </a>
        <a class="news-side-card reveal" href="https://sifinghana.org/page.php?id=3255" target="_blank" rel="noopener">
          <div class="nsc-image">
            <img src="https://sifinghana.org/backend/images/uploads/WhatsApp%20Image%202026-01-21%20at%204.15.48%20PM.jpeg1769013143.jpeg" alt="" data-fallback loading="lazy">
          </div>
          <div class="nsc-body">
            <span class="nsc-cat">Community</span>
            <h4>SIF hands over UG Biotechnology Centre site to contractors</h4>
            <span class="nsc-date">January 2026</span>
          </div>
        </a>
        <a class="news-side-card reveal" href="https://sifinghana.org/page.php?id=3253" target="_blank" rel="noopener">
          <div class="nsc-image">
            <img src="https://sifinghana.org/backend/images/uploads/1751046892_8d6c9a3176e1e094_SIFNEWPROJECTESMP.jpg" alt="" data-fallback loading="lazy">
          </div>
          <div class="nsc-body">
            <span class="nsc-cat">Environmental &amp; Social</span>
            <h4>Environmental &amp; Social Management Plan published</h4>
            <span class="nsc-date">2025</span>
          </div>
        </a>
      </div>
    </div>

    <div class="announce-strip reveal">
      <a class="announce-pill" href="/resources#procurement">
        <span class="aicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M9 3h6l-1 6h4l-9 12 2-9H7z"/></svg></span>
        <span><strong>Procurement Notices</strong><span>Open tenders &amp; contractor opportunities</span></span>
      </a>
      <a class="announce-pill" href="/resources#esg">
        <span class="aicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="9.5"/><path d="M12 7v5l3.5 2"/></svg></span>
        <span><strong>Public Consultations</strong><span>Community engagement schedules</span></span>
      </a>
      <a class="announce-pill" href="/resources#esg">
        <span class="aicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 21s-7-4.6-7-10.6A5.4 5.4 0 0 1 12 6a5.4 5.4 0 0 1 7 4.4C19 16.4 12 21 12 21Z"/></svg></span>
        <span><strong>Environmental Documents</strong><span>ESMPs &amp; safeguard reporting</span></span>
      </a>
      <a class="announce-pill" href="/contact">
        <span class="aicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 21h18M5 21V7l7-4 7 4v14"/></svg></span>
        <span><strong>Vacancies</strong><span>Current opportunities at SIF</span></span>
      </a>
      <a class="announce-pill" href="/resources#procurement">
        <span class="aicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 6.5 12 3l8 3.5-8 3.5-8-3.5Z"/></svg></span>
        <span><strong>Tender Opportunities</strong><span>For contractors &amp; suppliers</span></span>
      </a>
    </div>
  </div>
</section>


{{-- ===================== 9. PUBLICATIONS ===================== --}}
<section class="sec bg-dim" id="publications-preview">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Resource Centre</span>
      <h2>Reports, policies and project documentation.</h2>
    </div>
    <div class="doc-grid reveal-stagger">
      <a class="doc-card reveal" style="--i:0" href="https://sifinghana.org/images/DRAFT%20Social%20Investment%20Fund%20Corporate%20Profile.pdf" target="_blank" rel="noopener">
        <div class="doc-top"><div class="doc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 4h12l4 4v12H4z"/><path d="M16 4v4h4"/></svg></div><span class="doc-type">PDF</span></div>
        <h4>Corporate Profile</h4>
        <div class="doc-meta"><span>SIF Institutional</span><span>PDF</span></div>
        <span class="doc-action">Download <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v9m0 0 3.5-3.5M8 11 4.5 7.5M3 13.5h10"/></svg></span>
      </a>
      <a class="doc-card reveal" style="--i:1" href="https://sifinghana.org/annual-reports.php" target="_blank" rel="noopener">
        <div class="doc-top"><div class="doc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M5 3h14v18l-7-3-7 3Z"/></svg></div><span class="doc-type">Reports</span></div>
        <h4>Annual Reports</h4>
        <div class="doc-meta"><span>SIF Ghana</span><span>Archive</span></div>
        <span class="doc-action">View Archive <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v9m0 0 3.5-3.5M8 11 4.5 7.5M3 13.5h10"/></svg></span>
      </a>
      <a class="doc-card reveal" style="--i:2" href="https://sifinghana.org/page.php?id=3253" target="_blank" rel="noopener">
        <div class="doc-top"><div class="doc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="9.5"/><path d="M12 7v5l3.5 2"/></svg></div><span class="doc-type">ESMP</span></div>
        <h4>Environmental &amp; Social Management Plan</h4>
        <div class="doc-meta"><span>GWYESCO</span><span>2025</span></div>
        <span class="doc-action">Read More <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v9m0 0 3.5-3.5M8 11 4.5 7.5M3 13.5h10"/></svg></span>
      </a>
    </div>
    <div style="text-align:center;margin-top:36px;">
      <a href="/resources" class="btn-ghost" style="justify-content:center;">Visit the Resource Centre
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
      </a>
    </div>
  </div>
</section>


{{-- ===================== 10. PARTNERS ===================== --}}
<section class="partners-premium" aria-label="Development partners">
  <div class="container">
    <div class="partners-premium-inner">
      <p class="partners-kicker">Working Together for Sustainable Development</p>
      <div class="marquee" style="width:100%;max-width:900px;">
        <div class="marquee-track">
          <img src="https://sifinghana.org/backend/images/uploads/gov-logo.png1732797769.png" alt="Government of Ghana" data-fallback loading="lazy">
          <img src="https://sifinghana.org/backend/images/uploads/download%20%281%29.png1732798233.webp1738336933.webp" alt="African Development Bank" data-fallback loading="lazy">
          <img src="https://sifinghana.org/backend/images/uploads/images.png1732798305.png" alt="OPEC Fund for International Development" data-fallback loading="lazy">
          <img src="https://sifinghana.org/backend/images/uploads/BADEA-LOGO-EN-HORIZONTAL-POSITIVE-RGB-e1717766645442.jpg1732798349.jpg" alt="BADEA" data-fallback loading="lazy">
          <img src="https://sifinghana.org/backend/images/uploads/undp-logo-png_seeklogo-322648.png1732798147.webp1738336912.webp" alt="UNDP" data-fallback loading="lazy">
          <img src="https://sifinghana.org/backend/images/uploads/gov-logo.png1732797769.png" alt="" aria-hidden="true" data-fallback loading="lazy">
          <img src="https://sifinghana.org/backend/images/uploads/download%20%281%29.png1732798233.webp1738336933.webp" alt="" aria-hidden="true" data-fallback loading="lazy">
          <img src="https://sifinghana.org/backend/images/uploads/images.png1732798305.png" alt="" aria-hidden="true" data-fallback loading="lazy">
          <img src="https://sifinghana.org/backend/images/uploads/BADEA-LOGO-EN-HORIZONTAL-POSITIVE-RGB-e1717766645442.jpg1732798349.jpg" alt="" aria-hidden="true" data-fallback loading="lazy">
          <img src="https://sifinghana.org/backend/images/uploads/undp-logo-png_seeklogo-322648.png1732798147.webp1738336912.webp" alt="" aria-hidden="true" data-fallback loading="lazy">
        </div>
      </div>
    </div>
  </div>
</section>


{{-- ===================== 11. ACCOUNTABILITY ===================== --}}
<section class="sec bg-forest" id="accountability">
  <div class="container">
    <div class="accountability-premium reveal">
      <div class="acc-inner">
        <div>
          <p class="acc-eyebrow">Public Accountability</p>
          <h2>Your Voice Matters</h2>
          <p>Citizens, contractors and community leaders can submit complaints, feedback, enquiries or reports about any SIF programme. Every submission is logged, reviewed and tracked to resolution.</p>
          <div class="acc-assurances">
            <div class="acc-assurance">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
              Confidential by default
            </div>
            <div class="acc-assurance">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9.5"/><path d="M12 7v5l3.5 2"/></svg>
              Acknowledged within 5 working days
            </div>
            <div class="acc-assurance">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 5 5L20 7"/></svg>
              No retaliation, ever
            </div>
          </div>
        </div>
        <div class="acc-actions">
          <a class="acc-action" href="/complaint">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 14 8 2l6 12H2Z"/><path d="M8 6.5v3.2M8 11.6h.01"/></svg>
            <strong>Submit a Complaint</strong>
            <span>Guided 7-step form</span>
          </a>
          <a class="acc-action" href="tel:0800600555">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/></svg>
            <strong>Call Toll-Free</strong>
            <span>0800 600 555</span>
          </a>
          <a class="acc-action" href="/contact#contact-channels">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 21s-7-4.6-7-10.6A7 7 0 0 1 12 3a7 7 0 0 1 7 7.4C19 16.4 12 21 12 21Z"/><circle cx="12" cy="10.4" r="2.5"/></svg>
            <strong>Zonal Office</strong>
            <span>Find your nearest office</span>
          </a>
          <a class="acc-action" href="/complaint#track">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <strong>Track a Complaint</strong>
            <span>Using your reference number</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>


{{-- ===================== 12. NEWSLETTER ===================== --}}
<section class="sec bg-cream">
  <div class="container">
    <div class="nl-premium-wrap reveal">
      <span class="eyebrow" style="justify-content:center;">Stay Connected</span>
      <h3 style="margin-top:14px;">Stay Informed About SIF Programmes and Opportunities</h3>
      <p>Subscribe for the latest project updates, procurement notices and news from SIF Ghana.</p>
      <form class="nl-pill-form" onsubmit="event.preventDefault(); var btn=this.querySelector('button'); this.querySelector('input').value=''; btn.textContent='Subscribed ✓'; btn.style.background='var(--emerald)'; btn.style.color='#fff';">
        <input type="email" required placeholder="your@organisation.com" aria-label="Email address">
        <button type="submit">Subscribe</button>
      </form>
      <p class="nl-privacy-note">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
        No spam. Your data is never shared or sold. Unsubscribe at any time.
      </p>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script src="/js/sif-projects.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
function renderProjectMap(mapId, opts){
  opts = opts || {};
  var el = document.getElementById(mapId);
  if(!el || typeof L === 'undefined') return null;
  var projects = opts.projects || (typeof SIF_PROJECTS !== 'undefined' ? SIF_PROJECTS : []);
  var map = L.map(mapId, {scrollWheelZoom:false}).setView([7.9, -1.2], 6.3);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution:'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom:17
  }).addTo(map);

  var allBounds = [];
  var listEl = opts.listId ? document.getElementById(opts.listId) : null;
  var legendEl = opts.legendId ? document.getElementById(opts.legendId) : null;
  if(listEl) listEl.innerHTML = '';

  if(legendEl && typeof ZONE_LABELS !== 'undefined'){
    legendEl.innerHTML = Object.keys(ZONE_LABELS).map(function(z){
      return '<span class="zone-dot"><i style="background:'+ZONE_COLORS[z]+'"></i>'+ZONE_LABELS[z]+'</span>';
    }).join('');
  }

  var statusLabelMap = {new:'New Programme', ongoing:'Ongoing', completed:'Completed'};

  projects.forEach(function(p){
    (p.markers||[]).forEach(function(m){
      var color = (typeof ZONE_COLORS !== 'undefined' && ZONE_COLORS[p.zone]) ? ZONE_COLORS[p.zone] : '#087F5B';
      var icon = L.divIcon({
        className:'',
        html:'<div style="width:16px;height:16px;border-radius:50%;background:'+color+';border:3px solid #fff;box-shadow:0 2px 6px rgba(7,59,42,0.4);"></div>',
        iconSize:[16,16], iconAnchor:[8,8]
      });
      var marker = L.marker([m.lat, m.lng], {icon:icon}).addTo(map);
      var popupHtml = '<div class="map-pop-tag">'+(statusLabelMap[p.status]||'')+'</div>'+
        '<div class="map-pop-title">'+p.name+'</div>'+
        '<div class="map-pop-meta">'+m.city+'</div>'+
        (opts.linkToDetail !== false ? '<a class="map-pop-link" href="/projects/'+p.id+'">View Programme &rarr;</a>' : '');
      marker.bindPopup(popupHtml);
      allBounds.push([m.lat, m.lng]);

      if(listEl){
        var item = document.createElement('div');
        item.className = 'map-list-item';
        item.innerHTML = '<div class="mli-region">'+m.city+'</div><div class="mli-title">'+p.name+'</div>';
        item.addEventListener('click', function(){
          map.flyTo([m.lat, m.lng], 9, {duration:0.6});
          marker.openPopup();
          document.querySelectorAll('.map-list-item').forEach(function(i){ i.classList.remove('is-active'); });
          item.classList.add('is-active');
        });
        listEl.appendChild(item);
      }
    });
  });

  if(opts.singlePoint){
    var sp = opts.singlePoint;
    var icon2 = L.divIcon({className:'', html:'<div style="width:18px;height:18px;border-radius:50%;background:#073B2A;border:3px solid #fff;box-shadow:0 2px 8px rgba(7,59,42,0.45);"></div>', iconSize:[18,18], iconAnchor:[9,9]});
    var m2 = L.marker([sp.lat, sp.lng], {icon:icon2}).addTo(map).bindPopup('<div class="map-pop-title">'+sp.label+'</div>');
    allBounds.push([sp.lat, sp.lng]);
    setTimeout(function(){ m2.openPopup(); }, 300);
  }

  if(allBounds.length > 1){
    map.fitBounds(allBounds, {padding:[34,34]});
  } else if(allBounds.length === 1){
    map.setView(allBounds[0], 11);
  }

  setTimeout(function(){ map.invalidateSize(); }, 250);
  return map;
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  renderProjectMap('siteMap', {listId:'mapList', legendId:'zoneLegend'});
});
</script>
<script>
/* ---- Hero slider ---- */
(function () {
  var slides  = document.querySelectorAll('.hero-slide');
  var dots    = document.querySelectorAll('.slider-dot');
  var numEl   = document.getElementById('slideCurrentNum');
  var prevBtn = document.getElementById('sliderPrev');
  var nextBtn = document.getElementById('sliderNext');
  var hero    = document.querySelector('.hero');
  var content = document.querySelector('.hero-content');
  var badgeText = document.getElementById('heroBadgeText');
  var titleEl = document.getElementById('heroTitle');
  var ledeEl = document.getElementById('heroLede');
  var primaryCta = document.getElementById('heroPrimaryCta');
  var primaryText = document.getElementById('heroPrimaryText');
  var secondaryCta = document.getElementById('heroSecondaryCta');
  var total   = slides.length;
  var current = 0;
  var timer   = null;
  var INTERVAL = 6000;
  var slideContent = [
    {
      badge: 'Est. 1998 — An Agency of the Government of Ghana',
      title: 'Investing in People.<br>Strengthening Communities.<br><em>Transforming Ghana.</em>',
      lede: 'The Social Investment Fund mobilises resources and delivers inclusive economic and social development programmes that improve livelihoods across all sixteen regions of Ghana.',
      primaryLabel: 'Explore Our Impact',
      primaryHref: '#impact',
      secondaryLabel: 'View Current Projects',
      secondaryHref: '/projects'
    },
    {
      badge: 'GWYESCO — Women, Youth & Social Cohesion',
      title: 'Creating Pathways to Work.<br>Building Skills.<br><em>Growing Opportunity.</em>',
      lede: 'GWYESCO expands employment, entrepreneurship and social cohesion opportunities for women and young people in target communities.',
      primaryLabel: 'View GWYESCO',
      primaryHref: '/projects/gwyesco',
      secondaryLabel: 'Explore Programmes',
      secondaryHref: '/projects'
    },
    {
      badge: 'IRDP II — Rural Infrastructure Delivery',
      title: 'Connecting Communities.<br>Improving Access.<br><em>Delivering Infrastructure.</em>',
      lede: 'Through IRDP II, SIF supports rural infrastructure that improves access to essential services and strengthens local economic activity.',
      primaryLabel: 'View IRDP II',
      primaryHref: '/projects/irdp2',
      secondaryLabel: 'See Project Map',
      secondaryHref: '/projects#map'
    },
    {
      badge: 'PSDPEP — Skills & Productivity',
      title: 'Backing Talent.<br>Raising Productivity.<br><em>Opening Futures.</em>',
      lede: 'PSDPEP helps young people and workers gain practical skills that improve livelihoods and support Ghana’s post-COVID recovery.',
      primaryLabel: 'View PSDPEP',
      primaryHref: '/projects/psdpep',
      secondaryLabel: 'Read Impact Stories',
      secondaryHref: '#impact'
    }
  ];

  function pad(n){ return (n < 10 ? '0' : '') + n; }

  function updateContent(index){
    var item = slideContent[index];
    if(!item) return;
    function apply(){
      if(badgeText) badgeText.textContent = item.badge;
      if(titleEl) titleEl.innerHTML = item.title;
      if(ledeEl) ledeEl.textContent = item.lede;
      if(primaryText) primaryText.textContent = item.primaryLabel;
      if(primaryCta) primaryCta.setAttribute('href', item.primaryHref);
      if(secondaryCta){
        secondaryCta.textContent = item.secondaryLabel;
        secondaryCta.setAttribute('href', item.secondaryHref);
      }
      if(content) content.classList.remove('is-changing');
    }
    if(!content){
      apply();
      return;
    }
    content.classList.add('is-changing');
    window.setTimeout(apply, 180);
  }

  function goTo(n){
    var prev = current;
    current = (n + total) % total;
    if(prev === current) return;

    slides[prev].classList.remove('is-active');
    dots[prev].classList.remove('is-active');
    dots[prev].setAttribute('aria-selected', 'false');

    slides[current].classList.add('is-active');
    dots[current].classList.add('is-active');
    dots[current].setAttribute('aria-selected', 'true');

    if(numEl) numEl.textContent = pad(current + 1);
    updateContent(current);
  }

  function startAuto(){ timer = setInterval(function(){ goTo(current + 1); }, INTERVAL); }
  function stopAuto(){ clearInterval(timer); timer = null; }
  function restart(){ stopAuto(); startAuto(); }

  dots.forEach(function(dot){
    dot.addEventListener('click', function(){
      goTo(parseInt(dot.getAttribute('data-goto'), 10));
      restart();
    });
  });
  if(prevBtn) prevBtn.addEventListener('click', function(){ goTo(current - 1); restart(); });
  if(nextBtn) nextBtn.addEventListener('click', function(){ goTo(current + 1); restart(); });

  /* pause on hover / touch */
  if(hero){
    hero.addEventListener('mouseenter', stopAuto);
    hero.addEventListener('mouseleave', function(){ if(!timer) startAuto(); });
    hero.addEventListener('focusin',  stopAuto);
    hero.addEventListener('focusout', function(){ if(!timer) startAuto(); });
  }

  /* swipe support */
  var touchStartX = 0;
  if(hero){
    hero.addEventListener('touchstart', function(e){ touchStartX = e.changedTouches[0].clientX; }, {passive:true});
    hero.addEventListener('touchend', function(e){
      var dx = e.changedTouches[0].clientX - touchStartX;
      if(Math.abs(dx) > 44){ goTo(dx < 0 ? current + 1 : current - 1); restart(); }
    }, {passive:true});
  }

  startAuto();
})();
</script>
@endpush
