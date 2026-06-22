@extends('layouts.app')

@section('title', 'Contact Us | SIF Ghana')
@section('description', 'Contact the Social Investment Fund Ghana — address, telephone, email, and office locations.')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current">Contact</span></div>
    <span class="eyebrow on-dark">Get In Touch</span>
    <h1>Visit, call or write to us.</h1>
    <p>SIF's Fund Management Unit is based in Accra, with offices reaching all sixteen regions through four operational zones.</p>
  </div>
</section>

<section class="sec bg-white">
  <div class="container">
    <div class="contact-grid reveal-stagger">
      <div class="contact-cell reveal" style="--i:0">
        <div class="cicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 21s-7-4.6-7-10.6A7 7 0 0 1 12 3a7 7 0 0 1 7 7.4C19 16.4 12 21 12 21Z"/><circle cx="12" cy="10.4" r="2.5"/></svg></div>
        <h4>Visit Us</h4>
        <span class="cline">Off El-Wak Stadium Road,</span>
        <span class="cline">near the Agricultural Engineering Directorate, Accra</span>
        <span class="cline">P.O. Box CT3919, Cantonments, Accra</span>
        <span class="cline">Digital Address: GL-090-8073</span>
      </div>
      <div class="contact-cell reveal" style="--i:1">
        <div class="cicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/></svg></div>
        <h4>Call Us</h4>
        <a class="cline" href="tel:+233302778920">+233 (0)302 778 920</a>
        <a class="cline" href="tel:+233302778403">+233 (0)302 778 403</a>
        <a class="cline" href="tel:+233243690350">Mobile: +233 (0)24 369 0350</a>
        <div class="tollfree"><span class="tf-label">Toll-Free</span><a class="tf-num" href="tel:0800600555">0800 600 555</a></div>
      </div>
      <div class="contact-cell reveal" style="--i:2">
        <div class="cicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 4h16v16H4z"/><path d="m4 6 8 7 8-7"/></svg></div>
        <h4>Other Channels</h4>
        <a class="cline" href="mailto:info@sifinghana.org">info@sifinghana.org</a>
        <a class="cline" href="https://api.whatsapp.com/send?phone=233203918036" target="_blank" rel="noopener">WhatsApp: +233 20 391 8036</a>
        <a class="cline" href="https://www.facebook.com/profile.php?id=100066870500808" target="_blank" rel="noopener">Facebook · @SIF Ghana</a>
        <a class="cline" href="https://www.instagram.com/sifghana/" target="_blank" rel="noopener">Instagram · @sifghana</a>
      </div>
    </div>
  </div>
</section>

<!-- DEPARTMENT DIRECTORY + ZONAL OFFICES -->
<section class="sec bg-dim" id="contact-channels">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Zonal Offices</span>
      <h2>Find your nearest office.</h2>
      <p>SIF operates through four zones covering all sixteen regions. For department-specific enquiries, see our <a href="/departments" style="color:var(--emerald);font-weight:700;">Departments &amp; Units directory</a>.</p>
    </div>
    <div class="office-grid reveal-stagger">
      <div class="office-card reveal" style="--i:0;--zc:var(--zone-a);"><h4>Savannah Belt Zone</h4><span>Upper East · Upper West · North East · Savannah · Northern</span></div>
      <div class="office-card reveal" style="--i:1;--zc:var(--zone-b);"><h4>Forest &amp; Transition Zone</h4><span>Ashanti · Bono · Bono East · Ahafo</span></div>
      <div class="office-card reveal" style="--i:2;--zc:var(--zone-c);"><h4>Western Coast Zone</h4><span>Central · Western North · Western</span></div>
      <div class="office-card reveal" style="--i:3;--zc:var(--zone-d);"><h4>Eastern Seaboard Zone</h4><span>Eastern · Volta · Oti · Greater Accra (FMU)</span></div>
    </div>

    <div class="contact-map-shell reveal">
      <div id="siteMap" class="contact-map"></div>
    </div>
  </div>
</section>

<!-- ENQUIRY FORM + HOURS -->
<section class="sec bg-white">
  <div class="container">
    <div class="contact-form-layout">
      <div class="reveal">
        <span class="eyebrow">General Enquiries</span>
        <h2 class="section-head" style="margin:14px 0 24px;">Send us a message</h2>
        <form id="enquiryForm" onsubmit="event.preventDefault(); var btn=this.querySelector('button[type=submit]'); btn.textContent='Message Sent ✓'; btn.disabled=true;">
          <div class="form-row-2">
            <div class="field"><label for="enqName">Full Name</label><input type="text" id="enqName" required placeholder="Your name"></div>
            <div class="field"><label for="enqEmail">Email Address</label><input type="email" id="enqEmail" required placeholder="you@email.com"></div>
          </div>
          <div class="field"><label for="enqSubject">Subject</label><input type="text" id="enqSubject" required placeholder="What is this regarding?"></div>
          <div class="field"><label for="enqMessage">Message</label><textarea id="enqMessage" required placeholder="How can we help?"></textarea></div>
          <button type="submit" class="btn btn-primary btn-block">Send Message</button>
        </form>
      </div>
      <div class="contact-aside reveal" style="transition-delay:120ms;">
        <div class="contact-hours-card">
          <h4 style="font-size:15.5px;color:var(--forest);margin-bottom:14px;">Office Hours</h4>
          <table class="hours-table">
            <tr><td>Monday – Friday</td><td>8:00am – 5:00pm GMT</td></tr>
            <tr><td>Saturday – Sunday</td><td>Closed</td></tr>
            <tr><td>Toll-Free Line</td><td>24 hours</td></tr>
          </table>
          <p style="font-size:11.5px;color:var(--ink-soft);margin-top:10px;">Standard Ghanaian public-institution hours; some zonal offices may vary.</p>
        </div>
        <a href="tel:0800600555" class="btn btn-gold btn-block">Call Toll-Free Now</a>
        <a href="https://api.whatsapp.com/send?phone=233203918036" target="_blank" rel="noopener" class="btn btn-outline btn-block">Message Us on WhatsApp</a>
      </div>
    </div>
  </div>
</section>

@endsection

@push('scripts')
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
  renderProjectMap('siteMap', {
    projects: [],
    singlePoint: {lat: 5.6037, lng: -0.1870, label: "SIF Fund Management Unit — Off El-Wak Stadium Road, Accra"}
  });
});

</script>
@endpush
