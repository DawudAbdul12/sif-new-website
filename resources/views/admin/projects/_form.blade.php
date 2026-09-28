@csrf

@php
  $lineValue = fn ($value) => implode("\n", array_filter((array) old($value, $project->{$value} ?? [])));
  $documentsText = old('documents_text', collect($project->documents ?? [])->map(fn ($doc) => trim(($doc['label'] ?? '').' | '.($doc['url'] ?? '')))->implode("\n"));
  $markersText = old('markers_text', collect($project->markers ?? [])->map(fn ($marker) => trim(($marker['city'] ?? '').' | '.($marker['lat'] ?? '').' | '.($marker['lng'] ?? '')))->implode("\n"));
  $projectImage = old('image', $project->image);
  $selectedRegions = collect(old('regions', old('regions_text') ? preg_split('/\r\n|\r|\n/', old('regions_text')) : ($project->regions ?? [])))
    ->map(fn ($region) => trim((string) $region))
    ->filter()
    ->values()
    ->all();
@endphp

@push('styles')
  @once
    <style>
      .project-image-preview {
        align-items: center;
        aspect-ratio: 16 / 9;
        background: #f8faf7;
        border: 1px dashed rgba(23, 71, 45, .26);
        border-radius: 8px;
        color: var(--gb-muted);
        display: flex;
        font-weight: 700;
        justify-content: center;
        overflow: hidden;
        text-align: center;
      }
      .project-image-preview img {
        display: block;
        height: 100%;
        object-fit: cover;
        width: 100%;
      }
      .project-image-actions {
        display: grid;
        gap: 10px;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        margin: 12px 0;
      }
      .project-image-actions .admin-btn-secondary,
      .project-image-actions .admin-danger {
        font-size: .78rem;
        min-height: 40px;
        padding: 8px 10px;
      }
      .project-image-status {
        color: var(--gb-muted);
        font-size: .78rem;
        font-weight: 700;
        margin: 0 0 12px;
      }
      .project-library-modal-backdrop {
        align-items: center;
        background: rgba(15, 23, 42, .55);
        display: none;
        inset: 0;
        justify-content: center;
        padding: 20px;
        position: fixed;
        z-index: 1200;
      }
      .project-library-modal-backdrop.is-open { display: flex; }
      .project-library-modal {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 24px 80px rgba(15, 23, 42, .24);
        max-height: min(760px, 92vh);
        max-width: 920px;
        overflow: hidden;
        width: 100%;
      }
      .project-library-modal-header,
      .project-library-modal-footer {
        align-items: center;
        border-bottom: 1px solid var(--admin-gray-100);
        display: flex;
        gap: 14px;
        justify-content: space-between;
        padding: 16px 18px;
      }
      .project-library-modal-footer { border-bottom: 0; border-top: 1px solid var(--admin-gray-100); }
      .project-library-modal-header h2 {
        color: var(--admin-gray-900);
        font-size: 16px;
        font-weight: 850;
        margin: 0;
      }
      .project-library-close {
        align-items: center;
        background: #f8fafc;
        border: 1px solid var(--admin-gray-200);
        border-radius: 999px;
        color: var(--admin-gray-700);
        display: inline-flex;
        height: 36px;
        justify-content: center;
        width: 36px;
      }
      .project-library-modal-body {
        max-height: 560px;
        overflow: auto;
        padding: 18px;
      }
      .project-library-tools {
        display: flex;
        gap: 10px;
        margin-bottom: 14px;
      }
      .project-library-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(3, minmax(0, 1fr));
      }
      .project-library-item {
        background: #fff;
        border: 1px solid var(--admin-gray-200);
        border-radius: 10px;
        color: inherit;
        overflow: hidden;
        padding: 0;
        text-align: left;
      }
      .project-library-item.is-selected {
        border-color: var(--gb-green);
        box-shadow: 0 0 0 3px rgba(9, 167, 71, .12);
      }
      .project-library-item img {
        aspect-ratio: 4 / 3;
        display: block;
        object-fit: cover;
        width: 100%;
      }
      .project-library-item span {
        display: block;
        font-size: 12px;
        font-weight: 800;
        overflow: hidden;
        padding: 9px 10px;
        text-overflow: ellipsis;
        white-space: nowrap;
      }
      .project-region-picker {
        position: relative;
      }
      .project-region-control {
        align-items: center;
        background: #fff;
        border: 1px solid var(--admin-gray-200);
        border-radius: 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        min-height: 46px;
        padding: 7px 10px;
      }
      #project-region-chips {
        display: contents;
      }
      .project-region-control:focus-within {
        border-color: rgba(9, 167, 71, .58);
        box-shadow: 0 0 0 3px rgba(9, 167, 71, .12);
      }
      .project-region-chip {
        align-items: center;
        background: rgba(9, 167, 71, .1);
        border: 1px solid rgba(9, 167, 71, .18);
        border-radius: 999px;
        color: var(--gb-green);
        display: inline-flex;
        font-size: 12px;
        font-weight: 850;
        gap: 7px;
        padding: 6px 8px 6px 10px;
      }
      .project-region-chip button {
        align-items: center;
        background: rgba(9, 167, 71, .12);
        border: 0;
        border-radius: 999px;
        color: inherit;
        display: inline-flex;
        height: 18px;
        justify-content: center;
        padding: 0;
        width: 18px;
      }
      .project-region-search {
        border: 0;
        flex: 1 1 170px;
        font-size: 14px;
        min-width: 130px;
        outline: 0;
        padding: 7px 0;
      }
      .project-region-menu {
        background: #fff;
        border: 1px solid var(--admin-gray-200);
        border-radius: 10px;
        box-shadow: 0 18px 44px rgba(15, 23, 42, .14);
        display: none;
        max-height: 260px;
        overflow: auto;
        padding: 6px;
        position: fixed;
        z-index: 1300;
      }
      .project-region-picker.is-open .project-region-menu {
        display: grid;
        gap: 4px;
      }
      .project-region-option {
        align-items: center;
        background: transparent;
        border: 0;
        border-radius: 8px;
        color: var(--admin-gray-700);
        display: flex;
        font-size: 13px;
        font-weight: 800;
        justify-content: space-between;
        padding: 10px 11px;
        text-align: left;
      }
      .project-region-option:hover,
      .project-region-option.is-selected {
        background: #f4faf6;
        color: var(--gb-green);
      }
      .project-region-empty {
        color: var(--admin-gray-400);
        font-size: 13px;
        font-weight: 700;
        padding: 12px;
      }
      .project-region-select {
        display: none;
      }
      @media (max-width: 700px) {
        .project-image-actions { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .project-library-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .project-library-tools { flex-direction: column; }
      }
    </style>
  @endonce
@endpush

<div class="row g-3 align-items-start">
  <div class="col-xl-8">
    <div class="admin-panel mb-3">
      <div class="admin-card-header">
        <div>
          <h2 class="admin-card-title">Project Card</h2>
          <div class="admin-card-sub">These fields feed the public projects grid and list view.</div>
        </div>
        <span class="health-pill info">Frontend</span>
      </div>

      <div class="row g-3">
        <div class="col-md-4">
          <label class="admin-label" for="name">Short Name</label>
          <input id="name" name="name" value="{{ old('name', $project->name) }}" class="admin-control" placeholder="GWYESCO" required>
        </div>
        <div class="col-md-8">
          <label class="admin-label" for="full_name">Full Name</label>
          <input id="full_name" name="full_name" value="{{ old('full_name', $project->full_name) }}" class="admin-control" required>
        </div>
        <div class="col-md-4">
          <label class="admin-label" for="slug">Slug</label>
          <input id="slug" name="slug" value="{{ old('slug', $project->slug) }}" class="admin-control" placeholder="auto-generated">
        </div>
        <div class="col-md-4">
          <label class="admin-label" for="timeline">Timeline</label>
          <input id="timeline" name="timeline" value="{{ old('timeline', $project->timeline) }}" class="admin-control" placeholder="2026 - 2028">
        </div>
        <div class="col-md-4">
          <label class="admin-label" for="fund_amount">Fund Amount</label>
          <input id="fund_amount" name="fund_amount" value="{{ old('fund_amount', $project->fund_amount) }}" class="admin-control" placeholder="US$71.25M grant">
        </div>
        <div class="col-12">
          <label class="admin-label" for="image">Hero / Card Image URL</label>
          <div class="project-image-preview" id="project-image-preview">
            @if($projectImage)
              <img src="{{ $projectImage }}" alt="">
            @else
              <span><i class="bi bi-image d-block h3 mb-2"></i>No image selected</span>
            @endif
          </div>
          <input id="project_image_file" type="file" class="d-none" accept=".jpg,.jpeg,.png,.webp,.gif,.svg,image/jpeg,image/png,image/webp,image/gif,image/svg+xml">
          <div class="project-image-actions">
            <button type="button" class="admin-btn-secondary" id="project-image-upload"><i class="bi bi-upload"></i> Upload</button>
            <button type="button" class="admin-btn-secondary" id="project-image-library"><i class="bi bi-images"></i> Library</button>
            <button type="button" class="admin-btn-secondary" id="project-image-refresh"><i class="bi bi-arrow-clockwise"></i> Preview</button>
            <button type="button" class="admin-danger" id="project-image-clear"><i class="bi bi-trash"></i> Clear</button>
          </div>
          <div class="project-image-status" id="project-image-status">Upload a file, choose from the Media Library, or paste a URL below.</div>
          <input id="image" name="image" value="{{ $projectImage }}" class="admin-control" placeholder="/images/project.jpg or https://...">
        </div>
        <div class="col-12">
          <label class="admin-label" for="summary">Summary</label>
          <textarea id="summary" name="summary" class="admin-textarea" style="min-height: 150px" placeholder="Short public-facing project summary.">{{ old('summary', $project->summary) }}</textarea>
        </div>
      </div>
    </div>

    <div class="admin-panel mb-3">
      <div class="admin-card-header">
        <div>
          <h2 class="admin-card-title">Detail Page Content</h2>
          <div class="admin-card-sub">Shown on the individual project page beside the facts and map.</div>
        </div>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="beneficiaries">Beneficiaries</label>
        <textarea id="beneficiaries" name="beneficiaries" class="admin-textarea" style="min-height: 90px">{{ old('beneficiaries', $project->beneficiaries) }}</textarea>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="admin-label" for="objectives_text">Objectives</label>
          <textarea id="objectives_text" name="objectives_text" class="admin-textarea" placeholder="One objective per line">{{ old('objectives_text', $lineValue('objectives')) }}</textarea>
        </div>
        <div class="col-md-6">
          <label class="admin-label" for="outcomes_text">Key Outcomes</label>
          <textarea id="outcomes_text" name="outcomes_text" class="admin-textarea" placeholder="One outcome per line">{{ old('outcomes_text', $lineValue('outcomes')) }}</textarea>
        </div>
      </div>
    </div>

    <div class="admin-panel">
      <div class="admin-card-header">
        <div>
          <h2 class="admin-card-title">Documents, Related Projects & Map</h2>
          <div class="admin-card-sub">Use one row per entry. Documents: label | URL. Markers: city | latitude | longitude.</div>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="admin-label" for="documents_text">Related Documents</label>
          <textarea id="documents_text" name="documents_text" class="admin-textarea" placeholder="Programme Launch | https://example.com/file.pdf">{{ $documentsText }}</textarea>
        </div>
        <div class="col-md-6">
          <label class="admin-label" for="markers_text">Map Markers</label>
          <textarea id="markers_text" name="markers_text" class="admin-textarea" placeholder="Tamale, Northern Region | 9.4035 | -0.8393">{{ $markersText }}</textarea>
        </div>
        <div class="col-md-6">
          <label class="admin-label" for="related_projects_text">Related Project Slugs</label>
          <textarea id="related_projects_text" name="related_projects_text" class="admin-textarea" style="min-height: 110px" placeholder="psdpep&#10;irdp2">{{ old('related_projects_text', $lineValue('related_projects')) }}</textarea>
        </div>
        <div class="col-md-6">
          <label class="admin-label" for="regions_text">Regions</label>
          <div class="project-region-picker" id="project-region-picker">
            <select id="regions" name="regions[]" class="project-region-select" multiple>
              @foreach(\App\Models\Project::REGIONS as $region)
                <option value="{{ $region }}" @selected(in_array($region, $selectedRegions, true))>{{ $region }}</option>
              @endforeach
            </select>
            <div class="project-region-control" id="project-region-control">
              <div id="project-region-chips"></div>
              <input id="project-region-search" class="project-region-search" type="search" placeholder="Search and select regions" autocomplete="off">
            </div>
            <div class="project-region-menu" id="project-region-menu" role="listbox" aria-label="Project regions"></div>
          </div>
          <textarea id="regions_text" name="regions_text" class="d-none">{{ implode("\n", $selectedRegions) }}</textarea>
          <div class="small text-muted fw-bold mt-2">Select one or more Ghana regions.</div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-4">
    <div class="admin-panel mb-3">
      <div class="mb-3">
        <label class="admin-label" for="status">Publishing Status</label>
        <select id="status" name="status" class="admin-select">
          @foreach(\App\Models\Project::STATUSES as $status)
            <option value="{{ $status }}" @selected(old('status', $project->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="admin-label" for="project_status">Project State</label>
        <select id="project_status" name="project_status" class="admin-select">
          @foreach(\App\Models\Project::PROJECT_STATUSES as $status)
            <option value="{{ $status }}" @selected(old('project_status', $project->project_status ?: 'ongoing') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="admin-label" for="status_label">Status Label</label>
        <input id="status_label" name="status_label" value="{{ old('status_label', $project->status_label) }}" class="admin-control" placeholder="New · Launched 2026">
      </div>
      <div class="mb-3">
        <label class="admin-label" for="published_at">Publish Date</label>
        <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($project->published_at)->format('Y-m-d\TH:i')) }}" class="admin-control">
      </div>
      <div>
        <label class="admin-label" for="sort_order">Sort Order</label>
        <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $project->sort_order ?? 0) }}" class="admin-control">
      </div>
    </div>

    <div class="admin-panel mb-3">
      <div class="mb-3">
        <label class="admin-label" for="zone_key">Zone</label>
        <select id="zone_key" name="zone_key" class="admin-select">
          @foreach(\App\Models\Project::ZONES as $key => $label)
            <option value="{{ $key }}" @selected(old('zone_key', $project->zone_key ?: 'd') === $key)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="admin-label" for="zone_name">Custom Zone Label</label>
        <input id="zone_name" name="zone_name" value="{{ old('zone_name', $project->zone_name) }}" class="admin-control" placeholder="Defaults from zone">
      </div>
      <div class="mb-3">
        <label class="admin-label" for="funder">Funder</label>
        <input id="funder" name="funder" value="{{ old('funder', $project->funder) }}" class="admin-control">
      </div>
      <div>
        <label class="admin-label" for="categories_text">Frontend Filters</label>
        <textarea id="categories_text" name="categories_text" class="admin-textarea" style="min-height: 110px" placeholder="Infrastructure&#10;Employment">{{ old('categories_text', $lineValue('categories')) }}</textarea>
      </div>
    </div>

    <div class="admin-panel mb-3">
      <label class="admin-label" for="seo_title">SEO Title</label>
      <input id="seo_title" name="seo_title" value="{{ old('seo_title', $project->seo_title) }}" class="admin-control mb-3">
      <label class="admin-label" for="seo_description">SEO Description</label>
      <textarea id="seo_description" name="seo_description" class="admin-textarea" style="min-height: 110px">{{ old('seo_description', $project->seo_description) }}</textarea>
    </div>

    <div class="d-grid gap-2">
      <button type="submit" class="admin-btn"><i class="bi bi-check2"></i> Save Project</button>
      <a href="{{ route('admin.projects.index') }}" class="admin-btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
  </div>
</div>

<div class="project-library-modal-backdrop" id="project-library-modal" aria-hidden="true">
  <div class="project-library-modal" role="dialog" aria-modal="true" aria-labelledby="project-library-title">
    <div class="project-library-modal-header">
      <h2 id="project-library-title"><i class="bi bi-images"></i> Media Library</h2>
      <button class="project-library-close" type="button" id="project-library-close" aria-label="Close Media Library">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
    <div class="project-library-modal-body">
      <div class="project-library-tools">
        <input id="project-library-search" class="admin-control" type="search" placeholder="Search media library">
        <button class="admin-btn-secondary" type="button" id="project-library-refresh"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
      </div>
      <div class="project-library-grid" id="project-library-grid"></div>
      <div class="small text-muted fw-bold mt-2" id="project-library-status">Search or load the latest images.</div>
      <button type="button" class="admin-btn-secondary mt-2 w-100" id="project-library-more"><i class="bi bi-plus-lg"></i> Load More</button>
    </div>
    <div class="project-library-modal-footer">
      <span class="small text-muted" id="project-library-summary">Choose one image for the project hero and cards.</span>
      <button class="admin-btn" type="button" id="project-library-done"><i class="bi bi-check2"></i> Done</button>
    </div>
  </div>
</div>

@push('scripts')
  @once
    <script>
      (() => {
        const imageField = document.getElementById('image');
        const fileField = document.getElementById('project_image_file');
        const preview = document.getElementById('project-image-preview');
        const status = document.getElementById('project-image-status');
        const modal = document.getElementById('project-library-modal');
        const grid = document.getElementById('project-library-grid');
        const libraryStatus = document.getElementById('project-library-status');
        const moreButton = document.getElementById('project-library-more');
        const searchField = document.getElementById('project-library-search');
        const uploadUrl = @json(route('admin.editor.images.store'));
        const mediaUrl = @json(route('admin.editor.media.index'));
        const csrfToken = @json(csrf_token());
        let cursor = null;
        let hasMore = true;
        let search = '';
        let searchTimer = null;
        let loaded = false;

        if (!imageField || !preview) return;

        const escapeHtml = (value = '') => String(value).replace(/[&<>"']/g, (char) => ({
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#039;'
        }[char]));

        const setStatus = (message) => {
          if (status) status.textContent = message;
        };

        const setPreview = (url, message = 'Preview updated.') => {
          imageField.value = url || '';
          preview.innerHTML = url
            ? `<img src="${escapeHtml(url)}" alt="">`
            : '<span><i class="bi bi-image d-block h3 mb-2"></i>No image selected</span>';
          setStatus(message);
        };

        const initRegionPicker = () => {
          const picker = document.getElementById('project-region-picker');
          const select = document.getElementById('regions');
          const searchInput = document.getElementById('project-region-search');
          const menu = document.getElementById('project-region-menu');
          const chips = document.getElementById('project-region-chips');
          const hiddenText = document.getElementById('regions_text');
          const control = document.getElementById('project-region-control');

          if (!picker || !select || !searchInput || !menu || !chips || !hiddenText || !control) return;

          const options = Array.from(select.options).map((option) => option.value);

          const selected = () => Array.from(select.selectedOptions).map((option) => option.value);

          const syncHidden = () => {
            hiddenText.value = selected().join('\n');
          };

          const renderChips = () => {
            chips.innerHTML = selected().map((region) => `
              <span class="project-region-chip">
                ${escapeHtml(region)}
                <button type="button" data-region-remove="${escapeHtml(region)}" aria-label="Remove ${escapeHtml(region)}"><i class="bi bi-x"></i></button>
              </span>
            `).join('');
            syncHidden();
          };

          const renderMenu = () => {
            const query = searchInput.value.trim().toLowerCase();
            const current = new Set(selected());
            const matches = options.filter((region) => region.toLowerCase().includes(query));

            menu.innerHTML = matches.length
              ? matches.map((region) => `
                <button type="button" class="project-region-option${current.has(region) ? ' is-selected' : ''}" data-region="${escapeHtml(region)}" role="option" aria-selected="${current.has(region) ? 'true' : 'false'}">
                  <span>${escapeHtml(region)}</span>
                  ${current.has(region) ? '<i class="bi bi-check2"></i>' : ''}
                </button>
              `).join('')
              : '<div class="project-region-empty">No regions found.</div>';
          };

          const positionMenu = () => {
            if (!picker.classList.contains('is-open')) return;
            const rect = control.getBoundingClientRect();
            const availableBelow = window.innerHeight - rect.bottom - 12;
            const availableAbove = rect.top - 12;
            const openAbove = availableBelow < 180 && availableAbove > availableBelow;
            const maxHeight = Math.max(160, Math.min(260, openAbove ? availableAbove : availableBelow));

            menu.style.left = `${rect.left}px`;
            menu.style.width = `${rect.width}px`;
            menu.style.maxHeight = `${maxHeight}px`;
            menu.style.top = openAbove ? `${Math.max(12, rect.top - maxHeight - 6)}px` : `${rect.bottom + 6}px`;
          };

          const setRegion = (region, enabled) => {
            const option = Array.from(select.options).find((item) => item.value === region);
            if (!option) return;
            option.selected = enabled;
            renderChips();
            renderMenu();
          };

          picker.addEventListener('click', () => {
            picker.classList.add('is-open');
            searchInput.focus();
            renderMenu();
            positionMenu();
          });

          searchInput.addEventListener('focus', () => {
            picker.classList.add('is-open');
            renderMenu();
            positionMenu();
          });

          searchInput.addEventListener('input', () => {
            renderMenu();
            positionMenu();
          });

          menu.addEventListener('click', (event) => {
            const button = event.target.closest('[data-region]');
            if (!button) return;
            const region = button.dataset.region;
            setRegion(region, !selected().includes(region));
            searchInput.value = '';
            searchInput.focus();
            positionMenu();
          });

          chips.addEventListener('click', (event) => {
            const button = event.target.closest('[data-region-remove]');
            if (!button) return;
            event.stopPropagation();
            setRegion(button.dataset.regionRemove, false);
            searchInput.focus();
            positionMenu();
          });

          document.addEventListener('click', (event) => {
            if (!picker.contains(event.target)) picker.classList.remove('is-open');
          });

          window.addEventListener('resize', positionMenu);
          window.addEventListener('scroll', positionMenu, true);

          searchInput.addEventListener('keydown', (event) => {
            if (event.key === 'Backspace' && !searchInput.value) {
              const values = selected();
              if (values.length) setRegion(values[values.length - 1], false);
            }
          });

          renderChips();
          renderMenu();
        };

        initRegionPicker();

        const openModal = () => {
          if (!modal) return;
          modal.classList.add('is-open');
          modal.setAttribute('aria-hidden', 'false');
          document.body.style.overflow = 'hidden';
          searchField?.focus();
          if (!loaded) loadLibrary({ reset: true });
        };

        const closeModal = () => {
          if (!modal) return;
          modal.classList.remove('is-open');
          modal.setAttribute('aria-hidden', 'true');
          document.body.style.overflow = '';
          document.getElementById('project-image-library')?.focus();
        };

        const selectLibraryImage = (media, item) => {
          document.querySelectorAll('.project-library-item.is-selected').forEach((active) => active.classList.remove('is-selected'));
          item.classList.add('is-selected');
          setPreview(media.url || '', `${media.name || 'Media Library image'} selected.`);
          document.getElementById('project-library-summary').textContent = `${media.name || 'Selected image'} will be used when you save.`;
        };

        const renderLibraryItems = (items, append = false) => {
          if (!grid) return;
          if (!append) grid.innerHTML = '';

          if (!items.length && !append) {
            grid.innerHTML = '<div class="text-muted small">No library images found.</div>';
            return;
          }

          items.forEach((media) => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = `project-library-item${imageField.value === media.url ? ' is-selected' : ''}`;
            item.innerHTML = `<img src="${escapeHtml(media.url || '')}" alt=""><span>${escapeHtml(media.name || 'Untitled image')}</span>`;
            item.addEventListener('click', () => selectLibraryImage(media, item));
            grid.appendChild(item);
          });
        };

        const loadLibrary = async ({ reset = false } = {}) => {
          if (reset) {
            cursor = null;
            hasMore = true;
          }

          if (!hasMore && !reset) return;
          if (libraryStatus) libraryStatus.textContent = cursor ? 'Loading more images...' : 'Loading latest images...';
          if (moreButton) moreButton.disabled = true;

          try {
            const url = new URL(mediaUrl, window.location.origin);
            url.searchParams.set('type', 'images');
            url.searchParams.set('per_page', '18');
            url.searchParams.set('cursor_mode', '1');
            if (search) url.searchParams.set('search', search);
            if (cursor) url.searchParams.set('cursor', cursor.toString());

            const response = await fetch(url, { headers: { Accept: 'application/json' } });
            if (!response.ok) throw new Error('Media request failed');

            const payload = await response.json();
            loaded = true;
            renderLibraryItems(payload.data || [], Boolean(cursor));
            hasMore = Boolean(payload.meta.has_more);
            cursor = payload.meta.next_cursor || null;
            if (libraryStatus) libraryStatus.textContent = `${document.querySelectorAll('.project-library-item').length} images loaded.`;
            if (moreButton) {
              moreButton.hidden = !hasMore;
              moreButton.disabled = false;
            }
          } catch (error) {
            if (libraryStatus) libraryStatus.textContent = 'Could not load Media Library. Try again.';
            if (moreButton) {
              moreButton.hidden = false;
              moreButton.disabled = false;
            }
          }
        };

        const uploadImage = async () => {
          const file = fileField?.files?.[0];

          if (!file) {
            setStatus('Choose an image first.');
            return;
          }

          preview.innerHTML = `<img src="${escapeHtml(URL.createObjectURL(file))}" alt="">`;
          setStatus('Uploading image...');

          const payload = new FormData();
          payload.append('image', file);

          try {
            const response = await fetch(uploadUrl, {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
              },
              body: payload,
            });

            if (!response.ok) throw new Error('Upload failed');

            const image = await response.json();
            setPreview(image.url, 'Image uploaded to Media Library and selected.');
            loaded = false;
          } catch (error) {
            setStatus('Upload failed. Check file type and size, then try again.');
          }
        };

        imageField.addEventListener('input', () => {
          const value = imageField.value.trim();
          setPreview(value, value ? 'Preview updated from URL.' : 'Image cleared.');
        });

        document.getElementById('project-image-upload')?.addEventListener('click', () => fileField?.click());
        fileField?.addEventListener('change', uploadImage);
        document.getElementById('project-image-library')?.addEventListener('click', openModal);
        document.getElementById('project-image-refresh')?.addEventListener('click', () => setPreview(imageField.value.trim(), imageField.value.trim() ? 'Preview refreshed.' : 'No image selected.'));
        document.getElementById('project-image-clear')?.addEventListener('click', () => setPreview('', 'Image cleared.'));
        document.getElementById('project-library-close')?.addEventListener('click', closeModal);
        document.getElementById('project-library-done')?.addEventListener('click', closeModal);
        document.getElementById('project-library-refresh')?.addEventListener('click', () => loadLibrary({ reset: true }));
        moreButton?.addEventListener('click', () => loadLibrary());
        searchField?.addEventListener('input', (event) => {
          clearTimeout(searchTimer);
          search = event.target.value.trim();
          searchTimer = setTimeout(() => loadLibrary({ reset: true }), 300);
        });
        modal?.addEventListener('click', (event) => {
          if (event.target.id === 'project-library-modal') closeModal();
        });
        document.addEventListener('keydown', (event) => {
          if (event.key === 'Escape' && modal?.classList.contains('is-open')) closeModal();
        });
      })();
    </script>
  @endonce
@endpush
