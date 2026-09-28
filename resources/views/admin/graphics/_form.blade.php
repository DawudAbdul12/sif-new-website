@csrf

@push('styles')
  @once
    <style>
      .graphic-form-grid {
        align-items: start;
      }

      .graphic-form-section + .graphic-form-section {
        margin-top: 16px;
      }

      .graphic-panel-title {
        align-items: center;
        color: var(--gb-green);
        display: flex;
        font-size: 1rem;
        font-weight: 800;
        gap: 8px;
        margin: 0 0 18px;
      }

      .graphic-field-help {
        color: var(--gb-muted);
        font-size: .82rem;
        font-weight: 600;
        margin: 7px 0 0;
      }

      .graphic-side-panel {
        position: sticky;
        top: 24px;
      }

      .graphic-preview {
        align-items: center;
        aspect-ratio: 4 / 5;
        background:
          linear-gradient(45deg, rgba(23, 71, 45, .04) 25%, transparent 25%),
          linear-gradient(-45deg, rgba(23, 71, 45, .04) 25%, transparent 25%),
          linear-gradient(45deg, transparent 75%, rgba(23, 71, 45, .04) 75%),
          linear-gradient(-45deg, transparent 75%, rgba(23, 71, 45, .04) 75%);
        background-color: #f7faf5;
        background-position: 0 0, 0 8px, 8px -8px, -8px 0;
        background-size: 16px 16px;
        border: 1px solid #d8e1db;
        border-radius: 8px;
        display: flex;
        justify-content: center;
        margin-bottom: 14px;
        overflow: hidden;
      }

      .graphic-preview img {
        height: 100%;
        object-fit: contain;
        width: 100%;
      }

      .graphic-preview-placeholder {
        color: var(--gb-muted);
        font-size: .86rem;
        font-weight: 700;
        padding: 18px;
        text-align: center;
      }

      .graphic-preview-meta {
        background: #fbfcf8;
        border: 1px solid var(--gb-line);
        border-radius: 8px;
        display: grid;
        gap: 8px;
        margin-bottom: 16px;
        padding: 12px;
      }

      .graphic-preview-meta-row {
        align-items: center;
        color: var(--gb-muted);
        display: flex;
        font-size: .78rem;
        font-weight: 800;
        gap: 8px;
        min-width: 0;
      }

      .graphic-preview-meta-row span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }

      .graphic-upload-zone,
      .graphic-library-launch {
        align-items: center;
        background: #fbfcf8;
        border: 1px dashed #cbd6cf;
        border-radius: 8px;
        cursor: pointer;
        display: grid;
        gap: 10px;
        justify-items: center;
        min-height: 184px;
        padding: 28px 18px;
        text-align: center;
        transition: border-color .2s ease, box-shadow .2s ease;
      }

      .graphic-library-launch {
        border-style: solid;
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        min-height: 0;
        padding: 16px;
        text-align: left;
      }

      .graphic-upload-zone:hover,
      .graphic-upload-zone:focus-within {
        border-color: var(--gb-gold);
        box-shadow: 0 0 0 4px rgba(216, 180, 73, .12);
      }

      .graphic-upload-zone i {
        align-items: center;
        background: #edf5ef;
        border-radius: 999px;
        color: var(--gb-green);
        display: flex;
        font-size: 1.6rem;
        height: 56px;
        justify-content: center;
        width: 56px;
      }

      .graphic-upload-zone strong {
        color: var(--gb-green);
        display: block;
      }

      .graphic-upload-zone strong {
        font-size: .98rem;
      }

      .graphic-upload-zone span {
        color: var(--gb-muted);
        display: block;
        font-size: .82rem;
        margin-top: 2px;
      }

      .graphic-upload-zone input {
        height: 1px;
        opacity: 0;
        pointer-events: none;
        position: absolute;
        width: 1px;
      }

      .graphic-source-or {
        align-items: center;
        color: var(--gb-muted);
        display: flex;
        font-size: .78rem;
        font-weight: 800;
        gap: 10px;
        margin: 14px 0;
        text-transform: uppercase;
      }

      .graphic-source-or::before,
      .graphic-source-or::after {
        background: var(--gb-line);
        content: "";
        flex: 1;
        height: 1px;
      }

      .graphic-selected-file {
        color: var(--gb-green);
        font-size: .82rem;
        font-weight: 800;
        margin-top: 10px;
        min-height: 20px;
        text-align: center;
      }

      .graphic-position-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: 120px minmax(0, 1fr);
      }

      .graphic-position-note {
        align-items: center;
        background: #fbfcf8;
        border: 1px solid var(--gb-line);
        border-radius: 8px;
        color: var(--gb-muted);
        display: flex;
        font-size: .78rem;
        font-weight: 700;
        gap: 8px;
        padding: 11px 12px;
      }

      .graphic-action-bar {
        border-top: 1px solid var(--gb-line);
        display: grid;
        gap: 8px;
        margin: 18px -24px -24px;
        padding: 16px 24px 24px;
      }

      .graphic-library-modal-backdrop {
        align-items: center;
        background: rgba(12, 24, 17, .66);
        display: none;
        inset: 0;
        justify-content: center;
        padding: 24px;
        position: fixed;
        z-index: 1100;
      }

      .graphic-library-modal-backdrop.is-open {
        display: flex;
      }

      .graphic-library-modal {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 28px 70px rgba(0, 0, 0, .28);
        display: flex;
        flex-direction: column;
        max-height: 88vh;
        max-width: 1040px;
        overflow: hidden;
        width: min(100%, 1040px);
      }

      .graphic-library-modal-header,
      .graphic-library-modal-footer {
        align-items: center;
        display: flex;
        flex-shrink: 0;
        gap: 12px;
        justify-content: space-between;
        padding: 18px 20px;
      }

      .graphic-library-modal-header {
        border-bottom: 1px solid var(--gb-line);
      }

      .graphic-library-modal-header h2 {
        color: var(--gb-green);
        font-size: 1.08rem;
        font-weight: 800;
        margin: 0;
      }

      .graphic-library-modal-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        padding: 18px 20px;
      }

      .graphic-library-tools {
        display: grid;
        gap: 10px;
        grid-template-columns: minmax(0, 1fr) auto;
        margin-bottom: 12px;
      }

      .graphic-library-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      }

      .graphic-library-item {
        background: #fff;
        border: 1px solid var(--gb-line);
        border-radius: 8px;
        overflow: hidden;
        padding: 0;
        text-align: left;
      }

      .graphic-library-item.is-selected {
        border-color: var(--gb-gold);
        box-shadow: 0 0 0 3px rgba(216, 180, 73, .18);
      }

      .graphic-library-item img {
        aspect-ratio: 1;
        display: block;
        object-fit: contain;
        width: 100%;
      }

      .graphic-library-item span {
        color: var(--gb-green);
        display: block;
        font-size: .75rem;
        font-weight: 800;
        overflow: hidden;
        padding: 8px;
        text-overflow: ellipsis;
        white-space: nowrap;
      }

      .graphic-library-close {
        align-items: center;
        background: #fff;
        border: 1px solid var(--gb-line);
        border-radius: 999px;
        color: var(--gb-green);
        display: flex;
        height: 38px;
        justify-content: center;
        width: 38px;
      }

      @media (max-width: 991.98px) {
        .graphic-side-panel {
          position: static;
        }

        .graphic-position-grid {
          grid-template-columns: 1fr;
        }

        .graphic-library-tools {
          grid-template-columns: 1fr;
        }

        .graphic-library-launch {
          align-items: stretch;
          flex-direction: column;
        }
      }
    </style>
  @endonce
@endpush

<div class="row g-3 graphic-form-grid">
  <div class="col-lg-8">
    <div class="admin-panel graphic-form-section">
      <h2 class="graphic-panel-title"><i class="bi bi-card-text"></i> Graphic Details</h2>

      <div class="mb-3">
        <label class="admin-label" for="title">Title</label>
        <input id="title" name="title" value="{{ old('title', $graphic->title) }}" class="admin-control" required>
        <p class="graphic-field-help">Used as the public caption and the generated URL slug.</p>
        @error('title') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <input type="hidden" name="slug" value="{{ old('slug', $graphic->slug) }}">

      <div class="mb-3">
        <label class="admin-label" for="alt_text">Alt Text</label>
        <input id="alt_text" name="alt_text" value="{{ old('alt_text', $graphic->alt_text) }}" class="admin-control">
        <p class="graphic-field-help">Describe the image for accessibility. If empty, the title is used.</p>
        @error('alt_text') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="mb-0">
        <label class="admin-label" for="description">Description</label>
        <textarea id="description" name="description" class="admin-textarea" style="min-height: 160px">{{ old('description', $graphic->description) }}</textarea>
        @error('description') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="admin-panel graphic-form-section">
      <h2 class="graphic-panel-title"><i class="bi bi-image"></i> Graphic Artwork</h2>

      <label class="graphic-upload-zone" for="image">
        <div>
          <i class="bi bi-cloud-arrow-up"></i>
          <strong>Upload from computer</strong>
          <span>JPG, PNG, WebP or SVG. Maximum 5MB.</span>
          <div class="graphic-selected-file" id="graphic-selected-file"></div>
        </div>
        <input id="image" type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.svg,image/jpeg,image/png,image/webp,image/svg+xml">
      </label>
      @error('image') <div class="text-danger small mt-2">{{ $message }}</div> @enderror

      <div class="graphic-source-or">or</div>

      <div class="graphic-library-launch">
        <div>
          <h3 class="h6 fw-bold text-success mb-1">Choose from Media Library</h3>
          <p class="small text-muted mb-0" id="graphic-library-selected-summary">
            @if($graphic->mediaAsset)
              Selected: {{ $graphic->mediaAsset->name }}
            @else
              No Media Library graphic selected.
            @endif
          </p>
        </div>
        <button class="admin-btn-secondary" type="button" id="graphic-library-open"><i class="bi bi-folder2-open"></i> Browse</button>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="admin-panel graphic-side-panel">
      <h2 class="graphic-panel-title"><i class="bi bi-sliders"></i> Publishing</h2>

      <div class="graphic-preview" id="graphic-preview">
        @if($graphic->image_path)
          <img src="{{ $graphic->imageUrl() }}" alt="{{ $graphic->alt_text ?: $graphic->title }}">
        @else
          <div class="graphic-preview-placeholder">
            <i class="bi bi-image d-block fs-2 mb-2 text-success"></i>
            Graphic preview appears here.
          </div>
        @endif
      </div>

      <div class="graphic-preview-meta">
        <div class="graphic-preview-meta-row">
          <i class="bi {{ $graphic->media_asset_id ? 'bi-folder2-open' : 'bi-upload' }}"></i>
          <span id="graphic-source-summary">{{ $graphic->media_asset_id ? 'Media Library image' : ($graphic->image_path ? 'Uploaded image' : 'No artwork selected') }}</span>
        </div>
        <div class="graphic-preview-meta-row">
          <i class="bi bi-link-45deg"></i>
          <span>{{ $graphic->exists ? route('pages.graphics') : 'Public page after save' }}</span>
        </div>
      </div>

      <input type="hidden" id="media_asset_id" name="media_asset_id" value="{{ old('media_asset_id', $graphic->media_asset_id) }}">

      <div class="mb-3">
        <label class="admin-label" for="status">Status</label>
        <select id="status" name="status" class="admin-select">
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(old('status', $graphic->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="published_at">Publish Date</label>
        <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($graphic->published_at)->format('Y-m-d\TH:i')) }}" class="admin-control">
      </div>

      <div class="graphic-position-grid mb-3">
        <div>
          <label class="admin-label" for="sort_order">Sort Order</label>
          <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $graphic->sort_order ?? 0) }}" class="admin-control">
        </div>
        <div class="graphic-position-note">
          <i class="bi bi-arrow-up-short"></i>
          <span>Lower numbers appear first on the public graphics page.</span>
        </div>
      </div>

      <div class="graphic-action-bar">
        <button type="submit" class="admin-btn w-100"><i class="bi bi-check2"></i> Save Graphic</button>
        <a href="{{ route('admin.graphics.index') }}" class="admin-btn-secondary w-100"><i class="bi bi-arrow-left"></i> Back</a>
      </div>
    </div>
  </div>
</div>

<div class="graphic-library-modal-backdrop" id="graphic-library-modal" aria-hidden="true">
  <div class="graphic-library-modal" role="dialog" aria-modal="true" aria-labelledby="graphic-library-title">
    <div class="graphic-library-modal-header">
      <h2 id="graphic-library-title"><i class="bi bi-images"></i> Media Library</h2>
      <button class="graphic-library-close" type="button" id="graphic-library-close" aria-label="Close Media Library">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
    <div class="graphic-library-modal-body">
      <div class="graphic-library-tools">
        <input id="graphic-library-search" class="admin-control" type="search" placeholder="Search media library">
        <button class="admin-btn-secondary" type="button" id="graphic-library-refresh"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
      </div>
      <div class="graphic-library-grid" id="graphic-library-grid"></div>
      <div class="small text-muted fw-bold mt-2" id="graphic-library-status">Search or load the latest images.</div>
      <button type="button" class="admin-btn-secondary mt-2 w-100" id="graphic-library-more"><i class="bi bi-plus-lg"></i> Load More</button>
    </div>
    <div class="graphic-library-modal-footer">
      <span class="small text-muted" id="graphic-library-modal-summary">Choose one image for this graphic.</span>
      <button class="admin-btn" type="button" id="graphic-library-done"><i class="bi bi-check2"></i> Done</button>
    </div>
  </div>
</div>

@push('scripts')
  @once
    <script>
      const graphicMediaUrl = @json(route('admin.editor.media.index'));
      let graphicLibraryCursor = null;
      let graphicLibraryHasMore = true;
      let graphicLibrarySearch = '';
      let graphicLibraryTimer = null;
      let graphicLibraryLoaded = false;

      function escapeGraphicHtml(value) {
        return String(value || '').replace(/[&<>"']/g, (char) => ({
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#039;'
        }[char]));
      }

      function setGraphicPreview(url, alt = '') {
        const preview = document.getElementById('graphic-preview');
        if (!preview) return;
        preview.innerHTML = `<img src="${escapeGraphicHtml(url)}" alt="${escapeGraphicHtml(alt)}">`;
      }

      function setGraphicSourceSummary(value) {
        const source = document.getElementById('graphic-source-summary');
        if (source) source.textContent = value;
      }

      document.getElementById('image')?.addEventListener('change', function(event) {
        const file = event.target.files?.[0];
        if (!file) return;
        const url = URL.createObjectURL(file);
        setGraphicPreview(url, file.name);
        document.getElementById('media_asset_id').value = '';
        document.getElementById('graphic-selected-file').textContent = file.name;
        document.getElementById('graphic-library-selected-summary').textContent = 'No Media Library graphic selected.';
        setGraphicSourceSummary('New upload');
      });

      function selectGraphicLibraryImage(media, item) {
        document.querySelectorAll('.graphic-library-item.is-selected').forEach((active) => active.classList.remove('is-selected'));
        item.classList.add('is-selected');
        document.getElementById('media_asset_id').value = media.id;
        document.getElementById('image').value = '';
        document.getElementById('graphic-selected-file').textContent = '';
        document.getElementById('graphic-library-selected-summary').textContent = `Selected: ${media.name || 'Untitled image'}`;
        document.getElementById('graphic-library-modal-summary').textContent = `${media.name || 'Selected image'} will be used when you save.`;
        setGraphicPreview(media.url, media.alt_text || media.name || '');
        setGraphicSourceSummary('Media Library image');
      }

      function renderGraphicLibraryItems(items, append = false) {
        const grid = document.getElementById('graphic-library-grid');
        if (!grid) return;
        if (!append) grid.innerHTML = '';
        if (!items.length && !append) {
          grid.innerHTML = '<div class="text-muted small">No library images found.</div>';
          return;
        }

        items.forEach((media) => {
          const item = document.createElement('button');
          item.type = 'button';
          item.className = `graphic-library-item${Number(document.getElementById('media_asset_id').value) === Number(media.id) ? ' is-selected' : ''}`;
          item.innerHTML = `<img src="${escapeGraphicHtml(media.url)}" alt=""><span>${escapeGraphicHtml(media.name || 'Untitled image')}</span>`;
          item.addEventListener('click', () => selectGraphicLibraryImage(media, item));
          grid.appendChild(item);
        });
      }

      async function loadGraphicLibrary({ reset = false } = {}) {
        const status = document.getElementById('graphic-library-status');
        const moreButton = document.getElementById('graphic-library-more');

        if (reset) {
          graphicLibraryCursor = null;
          graphicLibraryHasMore = true;
        }

        if (!graphicLibraryHasMore && !reset) return;
        if (status) status.textContent = graphicLibraryCursor ? 'Loading more images...' : 'Loading latest images...';
        if (moreButton) moreButton.disabled = true;

        try {
          const url = new URL(graphicMediaUrl, window.location.origin);
          url.searchParams.set('type', 'images');
          url.searchParams.set('per_page', '18');
          url.searchParams.set('cursor_mode', '1');
          if (graphicLibrarySearch) url.searchParams.set('search', graphicLibrarySearch);
          if (graphicLibraryCursor) url.searchParams.set('cursor', graphicLibraryCursor.toString());

          const response = await fetch(url, { headers: { Accept: 'application/json' } });
          if (!response.ok) throw new Error('Media request failed');

          const payload = await response.json();
          graphicLibraryLoaded = true;
          renderGraphicLibraryItems(payload.data || [], Boolean(graphicLibraryCursor));
          graphicLibraryHasMore = Boolean(payload.meta.has_more);
          graphicLibraryCursor = payload.meta.next_cursor || null;
          if (status) status.textContent = `${document.querySelectorAll('.graphic-library-item').length} images loaded.`;
          if (moreButton) {
            moreButton.hidden = !graphicLibraryHasMore;
            moreButton.disabled = false;
          }
        } catch (error) {
          if (status) status.textContent = 'Could not load Media Library. Try again.';
          if (moreButton) {
            moreButton.hidden = false;
            moreButton.disabled = false;
          }
        }
      }

      function openGraphicLibraryModal() {
        const modal = document.getElementById('graphic-library-modal');
        if (!modal) return;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        document.getElementById('graphic-library-search')?.focus();
        if (!graphicLibraryLoaded) loadGraphicLibrary({ reset: true });
      }

      function closeGraphicLibraryModal() {
        const modal = document.getElementById('graphic-library-modal');
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        document.getElementById('graphic-library-open')?.focus();
      }

      document.getElementById('graphic-library-open')?.addEventListener('click', openGraphicLibraryModal);
      document.getElementById('graphic-library-close')?.addEventListener('click', closeGraphicLibraryModal);
      document.getElementById('graphic-library-done')?.addEventListener('click', closeGraphicLibraryModal);
      document.getElementById('graphic-library-refresh')?.addEventListener('click', () => loadGraphicLibrary({ reset: true }));
      document.getElementById('graphic-library-more')?.addEventListener('click', () => loadGraphicLibrary());
      document.getElementById('graphic-library-search')?.addEventListener('input', (event) => {
        clearTimeout(graphicLibraryTimer);
        graphicLibrarySearch = event.target.value.trim();
        graphicLibraryTimer = setTimeout(() => loadGraphicLibrary({ reset: true }), 300);
      });
      document.getElementById('graphic-library-modal')?.addEventListener('click', (event) => {
        if (event.target.id === 'graphic-library-modal') closeGraphicLibraryModal();
      });
      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && document.getElementById('graphic-library-modal')?.classList.contains('is-open')) {
          closeGraphicLibraryModal();
        }
      });
    </script>
  @endonce
@endpush
