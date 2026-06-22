@extends('layouts.app')

@section('title', 'Submit a Complaint | SIF Ghana')
@section('description', 'Submit a complaint or feedback to the Social Investment Fund Ghana using our secure online form.')

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m4 2 4 4-4 4"/></svg><span class="current">Submit a Complaint</span></div>
    <span class="eyebrow on-dark">Public Accountability</span>
    <h1>Submit a Complaint</h1>
    <p>A guided, confidential process — every submission is logged, reviewed and tracked to resolution.</p>
  </div>
</section>

<section class="sec bg-white">
  <div class="container-narrow">

    <div class="reassure-row reveal" style="margin-bottom:36px;justify-content:center;">
      <span class="ri"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>Confidential by default</span>
      <span class="ri"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9.5"/><path d="M12 7v5l3.5 2"/></svg>Acknowledged within 5 working days</span>
      <span class="ri"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 5 5L20 7"/></svg>No retaliation, ever</span>
    </div>

    <div id="formWrap">
      <div class="stepper" id="stepperNav">
        <div class="step is-active" data-step="1"><div class="step-dot">1</div><span class="step-label">Category</span></div>
        <div class="step-line"></div>
        <div class="step" data-step="2"><div class="step-dot">2</div><span class="step-label">Submitter</span></div>
        <div class="step-line"></div>
        <div class="step" data-step="3"><div class="step-dot">3</div><span class="step-label">Details</span></div>
        <div class="step-line"></div>
        <div class="step" data-step="4"><div class="step-dot">4</div><span class="step-label">Location</span></div>
        <div class="step-line"></div>
        <div class="step" data-step="5"><div class="step-dot">5</div><span class="step-label">Documents</span></div>
        <div class="step-line"></div>
        <div class="step" data-step="6"><div class="step-dot">6</div><span class="step-label">Contact</span></div>
        <div class="step-line"></div>
        <div class="step" data-step="7"><div class="step-dot">7</div><span class="step-label">Review</span></div>
      </div>

      <div class="complaint-panel">
        <form id="complaintForm" onsubmit="return false;">

          <!-- STEP 1 -->
          <div class="complaint-step is-current" data-step="1">
            <h3 style="font-size:19px;color:var(--forest);margin-bottom:6px;">What is your complaint about?</h3>
            <p style="color:var(--ink-soft);font-size:14px;margin-bottom:22px;">Choose the category that best fits your submission.</p>
            <div class="radio-row" style="flex-direction:column;">
              <label class="radio-card"><input type="radio" name="category" value="General Enquiry"><span><strong>General Enquiry</strong><span>Questions about SIF or its programmes</span></span></label>
              <label class="radio-card"><input type="radio" name="category" value="Service Complaint"><span><strong>Service Complaint</strong><span>Issue with how a service was delivered</span></span></label>
              <label class="radio-card"><input type="radio" name="category" value="Project-Related Complaint"><span><strong>Project-Related Complaint</strong><span>Concern about a specific SIF project or contractor</span></span></label>
              <label class="radio-card"><input type="radio" name="category" value="Procurement Concern"><span><strong>Procurement Concern</strong><span>Issue with a tender or procurement process</span></span></label>
              <label class="radio-card"><input type="radio" name="category" value="Whistleblower Report"><span><strong>Whistleblower Report</strong><span>Report suspected fraud, abuse or misconduct</span></span></label>
            </div>
          </div>

          <!-- STEP 2 -->
          <div class="complaint-step" data-step="2">
            <h3 style="font-size:19px;color:var(--forest);margin-bottom:6px;">How would you like to submit this?</h3>
            <p style="color:var(--ink-soft);font-size:14px;margin-bottom:22px;">Anonymous submissions are accepted and treated with the same diligence.</p>
            <div class="radio-row">
              <label class="radio-card"><input type="radio" name="submitterType" value="Named"><span><strong>Submit with my name</strong><span>We may contact you for follow-up</span></span></label>
              <label class="radio-card"><input type="radio" name="submitterType" value="Anonymous"><span><strong>Submit anonymously</strong><span>No identifying details required</span></span></label>
            </div>
          </div>

          <!-- STEP 3 -->
          <div class="complaint-step" data-step="3">
            <h3 style="font-size:19px;color:var(--forest);margin-bottom:18px;">Tell us what happened</h3>
            <div class="field"><label for="cSubject">Subject</label><input type="text" id="cSubject" placeholder="Brief summary of your complaint"></div>
            <div class="field"><label for="cDetails">Details<span class="hint"> — be as specific as you can, including dates if known</span></label><textarea id="cDetails" placeholder="Describe what happened…"></textarea></div>
          </div>

          <!-- STEP 4 -->
          <div class="complaint-step" data-step="4">
            <h3 style="font-size:19px;color:var(--forest);margin-bottom:18px;">Where did this happen?</h3>
            <div class="form-row-2">
              <div class="field"><label for="cRegion">Region</label><select id="cRegion"><option value="">Select a region…</option><option>Greater Accra</option><option>Ashanti</option><option>Northern</option><option>Western</option><option>Central</option><option>Eastern</option><option>Volta</option><option>Bono</option><option>Upper East</option><option>Upper West</option><option>Other / Not Sure</option></select></div>
              <div class="field"><label for="cProject">Related Project <span class="hint">(optional)</span></label><select id="cProject"><option value="">Not applicable</option></select></div>
            </div>
          </div>

          <!-- STEP 5 -->
          <div class="complaint-step" data-step="5">
            <h3 style="font-size:19px;color:var(--forest);margin-bottom:6px;">Supporting documents</h3>
            <p style="color:var(--ink-soft);font-size:14px;margin-bottom:18px;">Optional. Attach photos or documents that support your complaint.</p>
            <label class="upload-zone" style="display:block;cursor:pointer;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 16V4m0 0 4 4m-4-4-4 4"/><path d="M4 16v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"/></svg>
              <strong>Click to upload, or drag a file here</strong>
              <span>PDF, JPG or PNG, up to 10MB &middot; demo only — not connected to a live upload service</span>
              <input type="file" style="display:none;">
            </label>
          </div>

          <!-- STEP 6 -->
          <div class="complaint-step" data-step="6">
            <h3 style="font-size:19px;color:var(--forest);margin-bottom:18px;">How should we follow up?</h3>
            <div class="radio-row">
              <label class="radio-card"><input type="radio" name="contactPref" value="Phone"><span><strong>Call me</strong><span>We'll phone you with updates</span></span></label>
              <label class="radio-card"><input type="radio" name="contactPref" value="Email"><span><strong>Email me</strong><span>We'll email you with updates</span></span></label>
              <label class="radio-card"><input type="radio" name="contactPref" value="None"><span><strong>No follow-up needed</strong><span>Just track using my reference number</span></span></label>
            </div>
            <div id="contactFields" style="margin-top:18px;display:none;">
              <div class="form-row-2">
                <div class="field"><label for="cName">Full Name</label><input type="text" id="cName" placeholder="Your name"></div>
                <div class="field"><label for="cContact">Phone or Email</label><input type="text" id="cContact" placeholder="e.g. +233 24 000 0000"></div>
              </div>
            </div>
          </div>

          <!-- STEP 7 -->
          <div class="complaint-step" data-step="7">
            <h3 style="font-size:19px;color:var(--forest);margin-bottom:18px;">Review &amp; submit</h3>
            <div class="review-block" id="reviewBlock"></div>
            <p style="font-size:12.5px;color:var(--ink-soft);">By submitting, you confirm the information provided is accurate to the best of your knowledge.</p>
          </div>

          <div class="step-actions">
            <button type="button" class="btn btn-outline" id="btnBack" style="visibility:hidden;">Back</button>
            <button type="button" class="btn btn-primary" id="btnNext">Continue
              <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- CONFIRMATION -->
    <div id="confirmWrap" style="display:none;">
      <div class="complaint-panel confirm-panel">
        <div class="confirm-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 5 5L20 7"/></svg></div>
        <h3 style="font-size:22px;color:var(--forest);">Your complaint has been submitted</h3>
        <p style="color:var(--ink-soft);font-size:14.5px;max-width:420px;margin:10px auto 0;">Please save your reference number below to track the status of your submission.</p>
        <div class="confirm-ref" id="refNumber">SIF-2026-000000</div>
        <p style="color:var(--ink-soft);font-size:13px;max-width:420px;margin:0 auto 24px;">You can expect an initial acknowledgement within 5 working days. This is a front-end demonstration — no data has been sent to a live system.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
          <a href="/" class="btn btn-outline">Return Home</a>
          <button type="button" class="btn btn-primary" id="btnNewComplaint">Submit Another</button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TRACK A COMPLAINT -->
<section class="sec bg-dim" id="track">
  <div class="container-narrow">
    <div class="section-head reveal center">
      <span class="eyebrow">Already Submitted?</span>
      <h2>Track a Complaint</h2>
      <p>Enter your reference number to check the status of a previous submission.</p>
    </div>
    <div class="reveal" style="background:#fff;border:1px solid var(--line);border-radius:var(--radius-m);padding:28px;display:flex;gap:12px;flex-wrap:wrap;">
      <div class="field" style="flex:1;min-width:220px;margin-bottom:0;">
        <input type="text" id="trackInput" placeholder="e.g. SIF-2026-104822">
      </div>
      <button type="button" class="btn btn-primary" id="btnTrack">Check Status</button>
    </div>
    <div id="trackResult" style="display:none;margin-top:18px;background:#fff;border:1px solid var(--line);border-radius:var(--radius-m);padding:22px;">
      <p style="font-size:13px;color:var(--ink-soft);">This is a front-end demonstration. Reference number lookups are not connected to a live complaints system — please call our toll-free line on <a href="tel:0800600555" style="color:var(--emerald);font-weight:700;">0800 600 555</a> for a status update on a real submission.</p>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script src="/js/sif-projects.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  var totalSteps = 7;
  var current = 1;
  var steps = document.querySelectorAll('.step');
  var panels = document.querySelectorAll('.complaint-step');
  var btnBack = document.getElementById('btnBack');
  var btnNext = document.getElementById('btnNext');
  var formWrap = document.getElementById('formWrap');
  var confirmWrap = document.getElementById('confirmWrap');

  // populate project dropdown
  var cProject = document.getElementById('cProject');
  if(cProject && typeof SIF_PROJECTS !== 'undefined'){
    SIF_PROJECTS.forEach(function(p){
      var opt = document.createElement('option');
      opt.value = p.name; opt.textContent = p.fullName + ' (' + p.name + ')';
      cProject.appendChild(opt);
    });
  }

  // radio-card visual state
  document.querySelectorAll('.radio-card input[type=radio]').forEach(function(input){
    input.addEventListener('change', function(){
      var name = input.name;
      document.querySelectorAll('input[name="'+name+'"]').forEach(function(i){
        i.closest('.radio-card').classList.toggle('is-checked', i.checked);
      });
      if(name === 'contactPref'){
        var contactFields = document.getElementById('contactFields');
        contactFields.style.display = (input.value !== 'None') ? 'block' : 'none';
      }
    });
  });

  function showStep(n){
    panels.forEach(function(p){ p.classList.toggle('is-current', parseInt(p.dataset.step,10) === n); });
    steps.forEach(function(s){
      var sn = parseInt(s.dataset.step,10);
      s.classList.toggle('is-active', sn === n);
      s.classList.toggle('is-done', sn < n);
    });
    btnBack.style.visibility = n === 1 ? 'hidden' : 'visible';
    btnNext.innerHTML = n === totalSteps
      ? 'Submit Complaint <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 5 5L20 7" transform="translate(-4,-2) scale(0.7)"/><path d="m3 8 3 3 7-7"/></svg>'
      : 'Continue <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h10M9 4l4 4-4 4"/></svg>';
    if(n === totalSteps) populateReview();
    var panelTop = document.querySelector('.complaint-panel');
    if(panelTop) panelTop.scrollIntoView({behavior:'smooth', block:'start'});
  }

  function getVal(id){ var el = document.getElementById(id); return el ? el.value : ''; }
  function getRadio(name){ var el = document.querySelector('input[name="'+name+'"]:checked'); return el ? el.value : '—'; }

  function populateReview(){
    var rows = [
      ['Category', getRadio('category')],
      ['Submission Type', getRadio('submitterType')],
      ['Subject', getVal('cSubject') || '—'],
      ['Region', getVal('cRegion') || '—'],
      ['Related Project', getVal('cProject') || 'Not applicable'],
      ['Follow-up Preference', getRadio('contactPref')]
    ];
    if(getRadio('contactPref') !== 'None'){
      rows.push(['Name', getVal('cName') || '—']);
      rows.push(['Contact', getVal('cContact') || '—']);
    }
    document.getElementById('reviewBlock').innerHTML = rows.map(function(r){
      return '<div class="review-row"><span>'+r[0]+'</span><span>'+r[1]+'</span></div>';
    }).join('');
  }

  btnNext.addEventListener('click', function(){
    if(current < totalSteps){
      current++;
      showStep(current);
    } else {
      // submit
      var ref = 'SIF-' + new Date().getFullYear() + '-' + Math.floor(100000 + Math.random()*900000);
      document.getElementById('refNumber').textContent = ref;
      formWrap.style.display = 'none';
      confirmWrap.style.display = 'block';
      confirmWrap.scrollIntoView({behavior:'smooth', block:'start'});
    }
  });

  btnBack.addEventListener('click', function(){
    if(current > 1){ current--; showStep(current); }
  });

  document.getElementById('btnNewComplaint').addEventListener('click', function(){
    document.getElementById('complaintForm').reset();
    document.querySelectorAll('.radio-card.is-checked').forEach(function(c){ c.classList.remove('is-checked'); });
    document.getElementById('contactFields').style.display = 'none';
    current = 1;
    showStep(1);
    formWrap.style.display = 'block';
    confirmWrap.style.display = 'none';
    formWrap.scrollIntoView({behavior:'smooth', block:'start'});
  });

  var btnTrack = document.getElementById('btnTrack');
  if(btnTrack){
    btnTrack.addEventListener('click', function(){
      document.getElementById('trackResult').style.display = 'block';
    });
  }

  showStep(1);
});

</script>

</body>
</script>
@endpush
