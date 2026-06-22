@extends('layouts.app')

@section('title', 'Projects | SIF Ghana')
@section('description', 'Browse all Social Investment Fund Ghana projects — active, ongoing, and completed programmes across Ghana.')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current">Projects</span></div>
    <span class="eyebrow on-dark">Our Projects</span>
    <h1>Six flagship programmes, almost three decades of delivery.</h1>
    <p>Search, filter and explore every SIF programme — active and completed — funded by government and the continent&rsquo;s leading development institutions.</p>
  </div>
</section>

<section class="sec bg-white">
  <div class="container">
    <div class="toolbar reveal">
      <div class="search-bar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="projSearch" placeholder="Search projects by name or keyword…">
      </div>
      <select class="select-pill" id="regionFilter">
        <option value="">All Regions</option>
        <option>Greater Accra</option>
        <option>Northern</option>
        <option>Ashanti</option>
        <option>Western</option>
        <option>Upper East</option>
        <option>Upper West</option>
      </select>
      <div class="view-toggle">
        <button type="button" class="is-active" id="viewGrid"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="13" y="3" width="8" height="8" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/><rect x="13" y="13" width="8" height="8" rx="1.5"/></svg>Grid</button>
        <button type="button" id="viewList"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>List</button>
      </div>
    </div>

    <div class="filter-row reveal" id="filterChips">
      <button class="chip is-active" data-status="" data-category="">All Projects <span class="chip-count" id="countAll"></span></button>
      <button class="chip" data-status="ongoing" data-category="">Active</button>
      <button class="chip" data-status="completed" data-category="">Completed</button>
      <button class="chip" data-status="" data-category="Infrastructure">Infrastructure</button>
      <button class="chip" data-status="" data-category="Employment">Employment</button>
      <button class="chip" data-status="" data-category="Women and Youth">Women &amp; Youth</button>
      <button class="chip" data-status="" data-category="Community Development">Community Development</button>
    </div>

    <div class="projects-grid reveal-stagger" id="projectsGrid"></div>
    <div id="emptyState" style="display:none;text-align:center;padding:60px 20px;color:var(--ink-soft);">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="40" height="40" style="margin:0 auto 14px;opacity:0.5;"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
      <p>No projects match your filters. Try a different search term or clear the filters.</p>
    </div>

    <div class="pagination reveal">
      <button class="pg-arrow" disabled><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="m10 3-5 5 5 5"/></svg></button>
      <button class="is-active">1</button>
      <button class="pg-arrow" disabled><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 3 5 5-5 5"/></svg></button>
    </div>
  </div>
</section>

<!-- MAP -->
<section class="sec bg-dim" id="map">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Project Map</span>
      <h2>Explore programme locations across Ghana.</h2>
      <p>Markers are schematic, placed at the nearest major town for each documented operating area — not exact site coordinates.</p>
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

<section class="sec-tight bg-white">
  <div class="container">
    <div class="workforce reveal" style="background:var(--cream-dim);border-radius:var(--radius-l);padding:36px 40px;display:flex;align-items:center;justify-content:space-between;gap:30px;flex-wrap:wrap;">
      <h3 style="font-size:20px;">Built by Ghanaians, for Ghanaians.</h3>
      <div style="display:flex;gap:36px;flex-wrap:wrap;">
        <div><strong style="display:block;font-family:var(--font-head);font-size:24px;color:var(--forest);">400+</strong><span style="font-size:12.5px;color:var(--ink-soft);">Small-scale contractors engaged</span></div>
        <div><strong style="display:block;font-family:var(--font-head);font-size:24px;color:var(--forest);">5,000+</strong><span style="font-size:12.5px;color:var(--ink-soft);">Artisans employed on site</span></div>
        <div><strong style="display:block;font-family:var(--font-head);font-size:24px;color:var(--forest);">10,000+</strong><span style="font-size:12.5px;color:var(--ink-soft);">Unskilled labourers given work</span></div>
      </div>
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
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  var grid = document.getElementById('projectsGrid');
  var emptyState = document.getElementById('emptyState');
  var chips = document.querySelectorAll('#filterChips .chip');
  var searchInput = document.getElementById('projSearch');
  var regionFilter = document.getElementById('regionFilter');
  var viewGrid = document.getElementById('viewGrid');
  var viewList = document.getElementById('viewList');
  var countAll = document.getElementById('countAll');
  if(countAll) countAll.textContent = '('+SIF_PROJECTS.length+')';

  var statusLabelMap = {new:'new', ongoing:'ongoing', completed:'completed'};

  function cardHtml(p){
    var statusText = p.status === 'new' ? 'New' : (p.status === 'ongoing' ? 'Ongoing' : 'Completed');
    var regionsHtml = p.regions.slice(0,3).map(function(r){ return '<span>'+r+'</span>'; }).join('');
    return '<article class="project-card reveal is-visible" data-status="'+p.status+'" data-categories="'+p.category.join('|')+'" data-regions="'+p.regions.join('|')+'" data-name="'+p.name.toLowerCase()+' '+p.fullName.toLowerCase()+'">'+
      '<div class="pimg"><span class="status '+p.status+'">'+statusText+'</span><img src="'+p.image+'" alt="'+p.name+'" data-fallback loading="lazy"></div>'+
      '<div class="pbody">'+
        '<span class="pmeta">'+p.name+' &middot; '+p.timeline+'</span>'+
        '<h3>'+p.fullName+'</h3>'+
        '<p>'+p.summary+'</p>'+
        '<div class="pregions">'+regionsHtml+'</div>'+
        '<div class="pfund">Funded by <strong>'+p.funder+'</strong></div>'+
        '<a href="/projects/'+p.id+'" class="pfoot-link">View Programme <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg></a>'+
      '</div></article>';
  }

  function renderAll(){
    grid.innerHTML = SIF_PROJECTS.map(cardHtml).join('');
    attachImgFallback();
    applyFilters();
  }

  function attachImgFallback(){
    grid.querySelectorAll('img[data-fallback]').forEach(function(img){
      img.addEventListener('error', function(){
        var parent = img.closest('.pimg');
        if(parent) parent.classList.add('img-fallback');
      });
    });
  }

  var state = {status:'', category:'', region:'', search:''};

  function applyFilters(){
    var cards = grid.querySelectorAll('.project-card');
    var visibleCount = 0;
    cards.forEach(function(card){
      var matchesStatus = !state.status || card.dataset.status === state.status;
      var matchesCategory = !state.category || card.dataset.categories.split('|').indexOf(state.category) !== -1;
      var matchesRegion = !state.region || card.dataset.regions.split('|').indexOf(state.region) !== -1;
      var matchesSearch = !state.search || card.dataset.name.indexOf(state.search.toLowerCase()) !== -1;
      var show = matchesStatus && matchesCategory && matchesRegion && matchesSearch;
      card.style.display = show ? '' : 'none';
      if(show) visibleCount++;
    });
    emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
    grid.style.display = visibleCount === 0 ? 'none' : 'grid';
  }

  chips.forEach(function(chip){
    chip.addEventListener('click', function(){
      chips.forEach(function(c){ c.classList.remove('is-active'); });
      chip.classList.add('is-active');
      state.status = chip.dataset.status || '';
      state.category = chip.dataset.category || '';
      applyFilters();
    });
  });

  if(searchInput) searchInput.addEventListener('input', function(){ state.search = searchInput.value; applyFilters(); });
  if(regionFilter) regionFilter.addEventListener('change', function(){ state.region = regionFilter.value; applyFilters(); });

  if(viewGrid && viewList){
    viewGrid.addEventListener('click', function(){
      grid.classList.remove('list-mode');
      viewGrid.classList.add('is-active'); viewList.classList.remove('is-active');
    });
    viewList.addEventListener('click', function(){
      grid.classList.add('list-mode');
      viewList.classList.add('is-active'); viewGrid.classList.remove('is-active');
    });
  }

  // read query params on load
  var params = new URLSearchParams(window.location.search);
  var initStatus = params.get('status');
  var initCategory = params.get('category');
  if(initStatus || initCategory){
    chips.forEach(function(c){ c.classList.remove('is-active'); });
    var matched = false;
    chips.forEach(function(c){
      if((initStatus && c.dataset.status === initStatus) || (initCategory && c.dataset.category === initCategory)){
        c.classList.add('is-active');
        state.status = c.dataset.status || '';
        state.category = c.dataset.category || '';
        matched = true;
      }
    });
    if(!matched) chips[0].classList.add('is-active');
  }

  renderAll();
  renderProjectMap('siteMap', {listId:'mapList', legendId:'zoneLegend'});
});

</script>
</script>
@endpush
