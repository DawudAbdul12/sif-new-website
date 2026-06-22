(function(){
  "use strict";

  /* ---------- current year ---------- */
  var y = document.getElementById('year');
  if(y) y.textContent = new Date().getFullYear();

  /* ---------- image fallback ---------- */
  document.querySelectorAll('img[data-fallback]').forEach(function(img){
    img.addEventListener('error', function(){
      var parent = img.closest('.pimg,.nimg,.hero-bg,.brand,.foot-brand,.story-img,.leader-photo,.intro-media .imain') || img.parentElement;
      if(parent) parent.classList.add('img-fallback');
      img.style.opacity = '0';
    });
  });

  /* ---------- sticky header shadow ---------- */
  var header = document.getElementById('site-header');
  var sentinel = document.getElementById('top-sentinel');
  if(header && sentinel && 'IntersectionObserver' in window){
    var hObs = new IntersectionObserver(function(entries){
      entries.forEach(function(e){ header.classList.toggle('is-scrolled', !e.isIntersecting); });
    }, {rootMargin:"-1px 0px 0px 0px", threshold:0});
    hObs.observe(sentinel);
  } else if(header){
    header.classList.add('is-scrolled');
  }

  /* ---------- mobile nav ---------- */
  var burger = document.getElementById('burgerBtn');
  var mobileNav = document.getElementById('mobileNav');
  var mobileClose = document.getElementById('mobileNavClose');
  function openNav(){ mobileNav.classList.add('open'); burger.setAttribute('aria-expanded','true'); document.body.style.overflow='hidden'; }
  function closeNav(){ mobileNav.classList.remove('open'); burger.setAttribute('aria-expanded','false'); document.body.style.overflow=''; }
  if(burger){ burger.addEventListener('click', openNav); }
  if(mobileClose){ mobileClose.addEventListener('click', closeNav); }
  if(mobileNav){
    mobileNav.querySelector('.scrim') && mobileNav.querySelector('.scrim').addEventListener('click', closeNav);
    mobileNav.querySelectorAll('a').forEach(function(a){ a.addEventListener('click', closeNav); });
  }
  document.querySelectorAll('.m-group-head').forEach(function(btn){
    btn.addEventListener('click', function(){
      var group = btn.closest('.m-group');
      var wasOpen = group.classList.contains('open');
      document.querySelectorAll('.m-group.open').forEach(function(g){ g.classList.remove('open'); });
      if(!wasOpen) group.classList.add('open');
    });
  });

  /* ---------- accessibility: font size control ---------- */
  var fsScale = 1;
  function applyFs(){ document.documentElement.style.setProperty('--fs-scale', fsScale.toFixed(2)); }
  var fsInc = document.getElementById('fsInc');
  var fsDec = document.getElementById('fsDec');
  var fsReset = document.getElementById('fsReset');
  if(fsInc) fsInc.addEventListener('click', function(){ fsScale = Math.min(1.25, fsScale + 0.075); applyFs(); });
  if(fsDec) fsDec.addEventListener('click', function(){ fsScale = Math.max(0.875, fsScale - 0.075); applyFs(); });
  if(fsReset) fsReset.addEventListener('click', function(){ fsScale = 1; applyFs(); });

  /* ---------- site search ---------- */
  var SEARCH_INDEX = [
    {title:"About The SIF", url:"/about", snip:"Our institutional overview, history and legal mandate."},
    {title:"Mission, Vision & Values", url:"/about#mvv", snip:"The principles that guide every SIF engagement."},
    {title:"Strategic Objectives", url:"/about#objectives", snip:"What SIF is working to achieve through 2028."},
    {title:"Board of Directors", url:"/leadership#board", snip:"Independent governance and strategic oversight."},
    {title:"Chief Executive Officer", url:"/leadership#ceo", snip:"Abass Nurudeen, Chief Executive Officer of SIF."},
    {title:"Management Team", url:"/leadership#management", snip:"Senior leadership across finance, programmes and field operations."},
    {title:"Departments & Units", url:"/departments#departments", snip:"Administration, Finance, Procurement, M&E, Micro Credit, Internal Audit."},
    {title:"Zonal Offices", url:"/departments#zones", snip:"Four operational zones covering all sixteen regions."},
    {title:"Organisational Structure", url:"/departments#structure", snip:"How SIF is structured from the FMU down to zonal offices."},
    {title:"Projects Directory", url:"/projects", snip:"Search and filter every SIF programme, active and completed."},
    {title:"Project Map", url:"/projects#map", snip:"Interactive map of SIF activity across Ghana's regions."},
    {title:"GWYESCO", url:"/projects/gwyesco", snip:"Ghana Women & Youth Employment and Social Cohesion programme."},
    {title:"PSDPEP", url:"/projects/psdpep", snip:"Post-COVID-19 Skills Development & Productivity Enhancement Project."},
    {title:"IRDP II", url:"/projects/irdp2", snip:"Integrated Rural Development Project, Phase II."},
    {title:"IRDP I", url:"/projects/irdp1", snip:"Integrated Rural Development Project, Phase I."},
    {title:"UPRP", url:"/projects/uprp", snip:"Urban Poverty Reduction Project."},
    {title:"GPRP I & II", url:"/projects/gprp", snip:"Ghana Poverty Reduction Project, Phases I and II."},
    {title:"What We Do", url:"/#what-we-do", snip:"Six areas of institutional service competence."},
    {title:"Our Impact", url:"/#impact", snip:"Three decades of figures, region by region."},
    {title:"News & Media Centre", url:"/news", snip:"Press releases, project updates and announcements."},
    {title:"Resource Centre", url:"/resources", snip:"Annual reports, publications, policies and procurement notices."},
    {title:"Annual Reports", url:"/resources#reports", snip:"Yearly performance and financial reporting."},
    {title:"Corporate Profile (PDF)", url:"/resources#publications", snip:"SIF's official corporate profile document."},
    {title:"Procurement Notices", url:"/resources#procurement", snip:"Open tenders and contractor opportunities."},
    {title:"Environmental & Social Documents", url:"/resources#esg", snip:"ESMPs and safeguard documentation."},
    {title:"Frequently Asked Questions", url:"/resources#faq", snip:"Common questions about SIF's work and processes."},
    {title:"Submit a Complaint", url:"/complaint", snip:"File a complaint, enquiry or report in a few guided steps."},
    {title:"Track a Complaint", url:"/complaint#track", snip:"Check the status of a complaint using your reference number."},
    {title:"Contact Us", url:"/contact", snip:"Visit, call or write to SIF's Fund Management Unit in Accra."},
    {title:"Toll-Free Line", url:"/contact#contact-channels", snip:"0800 600 555 — free to call from any network in Ghana."}
  ];
  var searchTriggers = document.querySelectorAll('[data-search-trigger]');
  var searchOverlay = document.getElementById('searchOverlay');
  var searchInput = document.getElementById('searchInput');
  var searchResults = document.getElementById('searchResults');
  function renderResults(q){
    if(!searchResults) return;
    searchResults.innerHTML = '';
    var query = q.trim().toLowerCase();
    if(!query){ searchResults.innerHTML = '<div class="search-empty">Start typing to search projects, pages and documents.</div>'; return; }
    var matches = SEARCH_INDEX.filter(function(item){
      return item.title.toLowerCase().indexOf(query) !== -1 || item.snip.toLowerCase().indexOf(query) !== -1;
    }).slice(0,8);
    if(!matches.length){ searchResults.innerHTML = '<div class="search-empty">No matches for &ldquo;'+query.replace(/</g,'')+'&rdquo;. Try a project name or topic.</div>'; return; }
    matches.forEach(function(m){
      var a = document.createElement('a');
      a.href = m.url;
      a.innerHTML = '<span class="sr-title">'+m.title+'</span><span class="sr-snip">'+m.snip+'</span>';
      searchResults.appendChild(a);
    });
  }
  function openSearch(){
    if(!searchOverlay) return;
    searchOverlay.classList.add('open');
    renderResults('');
    setTimeout(function(){ searchInput && searchInput.focus(); }, 50);
    document.body.style.overflow = 'hidden';
  }
  function closeSearch(){
    if(!searchOverlay) return;
    searchOverlay.classList.remove('open');
    document.body.style.overflow = '';
  }
  searchTriggers.forEach(function(t){ t.addEventListener('click', openSearch); });
  if(searchOverlay){
    searchOverlay.querySelector('.scrim').addEventListener('click', closeSearch);
    var closeBtn = document.getElementById('searchClose');
    if(closeBtn) closeBtn.addEventListener('click', closeSearch);
  }
  if(searchInput){ searchInput.addEventListener('input', function(){ renderResults(searchInput.value); }); }
  document.addEventListener('keydown', function(e){
    if((e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA')){ e.preventDefault(); openSearch(); }
    if(e.key === 'Escape'){ closeSearch(); }
  });

  /* ---------- cookie consent ---------- */
  var cookieBanner = document.getElementById('cookieBanner');
  if(cookieBanner){
    var dismissed = false;
    setTimeout(function(){ if(!dismissed) cookieBanner.classList.add('show'); }, 900);
    var accept = document.getElementById('cookieAccept');
    var decline = document.getElementById('cookieDecline');
    function hideCookie(){ dismissed = true; cookieBanner.classList.remove('show'); }
    if(accept) accept.addEventListener('click', hideCookie);
    if(decline) decline.addEventListener('click', hideCookie);
  }

  /* ---------- accordion (FAQ) ---------- */
  document.querySelectorAll('.accordion-head').forEach(function(btn){
    btn.addEventListener('click', function(){
      var item = btn.closest('.accordion-item');
      item.classList.toggle('is-open');
    });
  });

  /* ---------- leadership card toggle ---------- */
  document.querySelectorAll('.leader-toggle').forEach(function(btn){
    btn.addEventListener('click', function(){
      btn.closest('.leader-card').classList.toggle('is-open');
    });
  });

  /* ---------- reveal on scroll ---------- */
  if('IntersectionObserver' in window){
    var rObs = new IntersectionObserver(function(entries){
      entries.forEach(function(e){
        if(e.isIntersecting){ e.target.classList.add('is-visible'); rObs.unobserve(e.target); }
      });
    }, {threshold:0.12, rootMargin:"0px 0px -40px 0px"});
    document.querySelectorAll('.reveal').forEach(function(el){ rObs.observe(el); });
  } else {
    document.querySelectorAll('.reveal').forEach(function(el){ el.classList.add('is-visible'); });
  }

  /* ---------- animated counters ---------- */
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  function animateCount(el){
    var target = parseFloat(el.getAttribute('data-count'));
    if(isNaN(target)) return;
    var isDecimal = (target % 1 !== 0);
    var start = null;
    var dur = 1400;
    if(reduceMotion){ el.textContent = isDecimal ? target.toFixed(1) : target.toLocaleString(); return; }
    function step(ts){
      if(!start) start = ts;
      var p = Math.min((ts - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      var val = target * eased;
      el.textContent = isDecimal ? val.toFixed(1) : Math.round(val).toLocaleString();
      if(p < 1) requestAnimationFrame(step);
      else el.textContent = isDecimal ? target.toFixed(1) : target.toLocaleString();
    }
    requestAnimationFrame(step);
  }
  if('IntersectionObserver' in window){
    var cObs = new IntersectionObserver(function(entries){
      entries.forEach(function(e){
        if(e.isIntersecting){ animateCount(e.target); cObs.unobserve(e.target); }
      });
    }, {threshold:0.6});
    document.querySelectorAll('[data-count]').forEach(function(el){ cObs.observe(el); });
  }

})();

