@extends('layouts.app')

@section('title', 'Project Detail | SIF Ghana')
@section('description', 'Detailed information about Social Investment Fund Ghana programmes and projects.')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<style>
.project-detail-shell{padding-top:32px;}
#projectDetail{min-width:0;}
.pd-hero-img{aspect-ratio:21/9;border-radius:var(--radius-l);margin-bottom:28px;}
.pd-layout{display:grid;grid-template-columns:minmax(0,1.6fr) minmax(320px,1fr);gap:44px;align-items:start;}
.pd-main{min-width:0;}
.pd-aside{display:flex;flex-direction:column;gap:18px;min-width:0;}
.pd-facts{background:var(--cream-dim);border-radius:var(--radius-m);padding:24px;}
.pd-contact{background:#fff;border:1px solid var(--line);border-radius:var(--radius-m);padding:22px;}
.pd-map{margin-top:36px;border-radius:var(--radius-l);overflow:hidden;border:1px solid var(--line);}
.pd-related-wrap{margin-top:64px;}
.pd-related-grid{grid-template-columns:repeat(2,minmax(0,1fr));}
.pd-title{font-size:clamp(26px,3.2vw,36px);margin:10px 0 18px;color:var(--forest);}
.pd-summary{color:var(--ink-soft);font-size:16px;line-height:1.7;}
.pd-section-title{font-size:18px;margin:32px 0 14px;color:var(--forest);}
.pd-body-text{color:var(--ink-soft);font-size:14.5px;line-height:1.65;}
@media (max-width:900px){
  .pd-layout{grid-template-columns:1fr;gap:28px;}
  .pd-related-grid{grid-template-columns:1fr !important;}
}
@media (max-width:560px){
  .project-detail-shell{padding-top:18px;overflow-x:hidden;}
  .project-detail-shell .container{padding:0 18px;}
  #projectDetail,.pd-layout,.pd-main,.pd-aside,.pd-facts,.pd-contact,.pd-map,.pd-related-wrap{width:100%;max-width:100%;min-width:0;}
  .pd-map,.pd-facts,.pd-contact,.pd-related-wrap{overflow:hidden;}
  .pd-main > *,.pd-aside > *{max-width:100%;min-width:0;}
  .page-hero .breadcrumb{overflow-x:auto;white-space:nowrap;padding-bottom:12px;}
  .pd-hero-img{aspect-ratio:4/3;border-radius:var(--radius-m);margin-bottom:24px;}
  .pd-title,.pd-summary,.pd-body-text,#pdObjectives,#pdOutcomes,#pdDocs{width:100%;max-width:320px;}
  .pd-title{font-size:26px;line-height:1.2;overflow-wrap:anywhere;}
  .pd-summary,.pd-body-text,#pdObjectives span,#pdOutcomes span,.doc-action{font-size:15px;overflow-wrap:anywhere;word-break:normal;}
  .pd-facts,.pd-contact{padding:18px;}
  .review-row{display:grid;grid-template-columns:1fr;gap:3px;}
  .review-row span:last-child{text-align:left;}
  .pregions{gap:6px;}
  .pregions span{white-space:normal;}
  #siteMap{height:260px !important;}
  .pd-related-wrap{margin-top:44px;}
  .pd-related-grid .project-card{width:100%;}
  .pd-related-grid .pbody,.pd-related-grid .pbody h3,.pd-related-grid .pbody p{min-width:0;overflow-wrap:anywhere;}
  .leaflet-container{max-width:100%;}
}
</style>
@endpush

@section('content')
<section class="page-hero" style="padding-bottom:0;">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><a href="/projects">Projects</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current" id="bcProjectName">Project</span></div>
  </div>
</section>

<section class="sec-tight bg-white project-detail-shell">
  <div class="container">
    <div id="notFound" style="display:none;text-align:center;padding:60px 20px;">
      <h2>Project not found</h2>
      <p style="color:var(--ink-soft);margin-top:10px;">We couldn&rsquo;t find that project. <a href="/projects" style="color:var(--emerald);font-weight:700;">View all projects →</a></p>
    </div>

    <div id="projectDetail">
      <div class="pimg pd-hero-img" id="pdHeroImgWrap">
        <span class="status" id="pdStatus"></span>
        <img id="pdHeroImg" src="" alt="" data-fallback loading="eager">
      </div>

      <div class="pd-layout">
        <div class="pd-main reveal">
          <span class="pmeta" id="pdMeta"></span>
          <h1 class="pd-title" id="pdTitle"></h1>
          <p class="pd-summary" id="pdSummary"></p>

          <h3 class="pd-section-title">Objectives</h3>
          <ul id="pdObjectives" style="display:flex;flex-direction:column;gap:10px;padding-left:0;"></ul>

          <h3 class="pd-section-title" style="margin-bottom:8px;">Beneficiaries</h3>
          <p class="pd-body-text" id="pdBeneficiaries"></p>

          <h3 class="pd-section-title">Key Outcomes</h3>
          <ul id="pdOutcomes" style="display:flex;flex-direction:column;gap:10px;padding-left:0;"></ul>

          <h3 class="pd-section-title" id="pdDocsHead" style="display:none;">Related Documents</h3>
          <div id="pdDocs" style="display:flex;flex-direction:column;gap:10px;"></div>

          <div class="pd-map" id="pdMap">
            <div id="siteMap" style="height:320px;"></div>
          </div>
        </div>

        <aside class="pd-aside reveal">
          <div class="pd-facts">
            <div class="review-row"><span>Programme</span><span id="pdName"></span></div>
            <div class="review-row"><span>Status</span><span id="pdStatusText"></span></div>
            <div class="review-row"><span>Timeline</span><span id="pdTimeline"></span></div>
            <div class="review-row"><span>Funder</span><span id="pdFunder"></span></div>
            <div class="review-row"><span>Amount</span><span id="pdAmount"></span></div>
            <div class="review-row"><span>Zone</span><span id="pdZone"></span></div>
            <div style="padding-top:14px;">
              <span style="font-size:12px;font-weight:700;color:var(--ink-soft);text-transform:uppercase;letter-spacing:0.04em;">Regions</span>
              <div class="pregions" style="margin-top:8px;" id="pdRegions"></div>
            </div>
          </div>

          <div class="pd-contact">
            <h4 style="font-size:14.5px;color:var(--forest);margin-bottom:10px;">Contact</h4>
            <p style="font-size:13px;color:var(--ink-soft);line-height:1.6;">For enquiries about this programme, contact SIF&rsquo;s Fund Management Unit.</p>
            <a class="cline" href="mailto:info@sifinghana.org" style="margin-top:10px;">info@sifinghana.org</a>
            <a class="cline" href="tel:0800600555">Toll-Free: 0800 600 555</a>
            <a href="/contact" class="btn btn-outline btn-sm" style="margin-top:10px;width:100%;justify-content:center;">Contact Us</a>
          </div>

          <a href="/complaint" class="cact" style="text-decoration:none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 14 8 2l6 12H2Z"/><path d="M8 6.5v3.2M8 11.6h.01"/></svg>
            <strong>Have a concern about this project?</strong>
            <span>Submit a complaint</span>
          </a>
        </aside>
      </div>

      <div class="pd-related-wrap">
        <h3 style="font-size:20px;margin-bottom:20px;color:var(--forest);">Related Projects</h3>
        <div class="projects-grid pd-related-grid" id="pdRelated"></div>
      </div>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script src="/js/sif-projects.js"></script>
{{-- PHP slug injected so JS can load the correct project --}}
<script>var PROJECT_SLUG = '{{ $slug }}';</script>
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
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  
  var id = (typeof PROJECT_SLUG !== 'undefined') ? PROJECT_SLUG : '';
  var project = SIF_PROJECTS.find(function(p){ return p.id === id; });

  if(!project){
    document.getElementById('projectDetail').style.display = 'none';
    document.getElementById('notFound').style.display = 'block';
    document.getElementById('bcProjectName').textContent = 'Not Found';
    document.title = 'Project Not Found — The Social Investment Fund';
    return;
  }

  var statusLabelMap = {new:'New', ongoing:'Ongoing', completed:'Completed'};
  document.title = project.fullName + ' — The Social Investment Fund';
  document.getElementById('bcProjectName').textContent = project.name;
  document.getElementById('pdHeroImg').src = project.image;
  document.getElementById('pdHeroImg').alt = project.fullName;
  document.getElementById('pdStatus').textContent = project.statusLabel;
  document.getElementById('pdStatus').className = 'status ' + project.status;
  document.getElementById('pdMeta').textContent = project.name + ' · ' + project.category.join(', ');
  document.getElementById('pdTitle').textContent = project.fullName;
  document.getElementById('pdSummary').textContent = project.summary;
  document.getElementById('pdBeneficiaries').textContent = project.beneficiaries;

  document.getElementById('pdObjectives').innerHTML = project.objectives.map(function(o){
    return '<li style="display:flex;gap:10px;align-items:flex-start;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#087F5B" stroke-width="2.2" style="flex:none;margin-top:3px;"><path d="m5 12 5 5L20 7"/></svg><span style="color:var(--ink-soft);font-size:14.5px;line-height:1.6;">'+o+'</span></li>';
  }).join('');

  document.getElementById('pdOutcomes').innerHTML = project.outcomes.map(function(o){
    return '<li style="display:flex;gap:10px;align-items:flex-start;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D6A72C" stroke-width="2.2" style="flex:none;margin-top:3px;"><path d="M12 2 4 6v6c0 5 3.4 8.7 8 10 4.6-1.3 8-5 8-10V6l-8-4Z"/></svg><span style="color:var(--ink-soft);font-size:14.5px;line-height:1.6;">'+o+'</span></li>';
  }).join('');

  if(project.docs && project.docs.length){
    document.getElementById('pdDocsHead').style.display = 'block';
    document.getElementById('pdDocs').innerHTML = project.docs.map(function(d){
      return '<a href="'+d.url+'" target="_blank" rel="noopener" class="doc-action" style="border:1px solid var(--line);border-radius:var(--radius-s);padding:14px 16px;justify-content:flex-start;">'+
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" width="16" height="16"><path d="M4 4h12l4 4v12H4z"/><path d="M16 4v4h4"/></svg>'+d.label+'</a>';
    }).join('');
  }

  document.getElementById('pdName').textContent = project.name;
  document.getElementById('pdStatusText').textContent = statusLabelMap[project.status];
  document.getElementById('pdTimeline').textContent = project.timeline;
  document.getElementById('pdFunder').textContent = project.funder;
  document.getElementById('pdAmount').textContent = project.fundAmount;
  document.getElementById('pdZone').textContent = project.zoneName;
  document.getElementById('pdRegions').innerHTML = project.regions.map(function(r){ return '<span>'+r+'</span>'; }).join('');

  // related projects
  var relatedWrap = document.getElementById('pdRelated');
  var related = (project.related || []).map(function(rid){ return SIF_PROJECTS.find(function(p){ return p.id === rid; }); }).filter(Boolean);
  relatedWrap.innerHTML = related.map(function(p){
    return '<article class="project-card"><div class="pimg"><span class="status '+p.status+'">'+statusLabelMap[p.status]+'</span><img src="'+p.image+'" alt="'+p.name+'" data-fallback loading="lazy"></div>'+
      '<div class="pbody"><span class="pmeta">'+p.name+'</span><h3>'+p.fullName+'</h3><p>'+p.summary+'</p>'+
      '<a href="/projects/'+p.id+'" class="pfoot-link">View Programme <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></a></div></article>';
  }).join('');
  relatedWrap.querySelectorAll('img[data-fallback]').forEach(function(img){
    img.addEventListener('error', function(){ var p = img.closest('.pimg'); if(p) p.classList.add('img-fallback'); });
  });

  renderProjectMap('siteMap', {projects:[project], linkToDetail:false});

  document.getElementById('pdHeroImg').addEventListener('error', function(){
    document.getElementById('pdHeroImgWrap').classList.add('img-fallback');
  });
});

</script>

</body>
</script>
@endpush
