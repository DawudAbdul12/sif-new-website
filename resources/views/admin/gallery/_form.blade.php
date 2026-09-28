@csrf

@push('styles')
  @once
    <style>
      .gallery-form-grid {
        align-items: start;
      }

      .gallery-panel-title {
        align-items: center;
        color: var(--gb-green);
        display: flex;
        font-size: 1rem;
        font-weight: 800;
        gap: 8px;
        margin: 0 0 18px;
      }

      .gallery-side-panel {
        position: sticky;
        top: 24px;
      }

      .gallery-upload-preview,
      .gallery-existing-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(auto-fill, minmax(132px, 1fr));
      }

      .gallery-upload-zone {
        align-items: center;
        background: #fbfcf8;
        border: 1px dashed #cbd6cf;
        border-radius: 8px;
        cursor: pointer;
        display: grid;
        gap: 10px;
        justify-items: center;
        padding: 26px 18px;
        text-align: center;
        transition: border-color .2s ease, box-shadow .2s ease;
      }

      .gallery-upload-zone:hover,
      .gallery-upload-zone:focus-within {
        border-color: var(--gb-gold);
        box-shadow: 0 0 0 4px rgba(216, 180, 73, .12);
      }

      .gallery-upload-zone i {
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

      .gallery-upload-zone strong {
        color: var(--gb-green);
        display: block;
        font-size: .96rem;
      }

      .gallery-upload-zone span {
        color: var(--gb-muted);
        display: block;
        font-size: .82rem;
        margin-top: 2px;
      }

      .gallery-upload-zone input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
      }

      .gallery-upload-summary {
        color: var(--gb-muted);
        font-size: .82rem;
        font-weight: 700;
        margin-top: 10px;
      }

      .gallery-library-tools {
        align-items: center;
        display: grid;
        gap: 10px;
        grid-template-columns: minmax(0, 1fr) auto;
        margin-bottom: 12px;
      }

      .gallery-library-launch {
        align-items: center;
        background: #fbfcf8;
        border: 1px solid var(--gb-line);
        border-radius: 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: space-between;
        padding: 14px;
      }

      .gallery-library-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        padding-right: 4px;
      }

      .gallery-library-item {
        background: #fff;
        border: 1px solid var(--gb-line);
        border-radius: 8px;
        overflow: hidden;
        padding: 0;
        text-align: left;
      }

      .gallery-library-item.is-selected {
        border-color: var(--gb-gold);
        box-shadow: 0 0 0 3px rgba(216, 180, 73, .18);
      }

      .gallery-library-item img {
        aspect-ratio: 1;
        display: block;
        object-fit: cover;
        width: 100%;
      }

      .gallery-library-item span {
        color: var(--gb-green);
        display: block;
        font-size: .75rem;
        font-weight: 800;
        overflow: hidden;
        padding: 8px;
        text-overflow: ellipsis;
        white-space: nowrap;
      }

      .gallery-library-actions {
        display: grid;
        gap: 8px;
        grid-template-columns: 1fr 1fr;
        padding: 8px;
      }

      .gallery-library-actions button {
        border-radius: 6px;
        font-size: .72rem;
        padding: 7px 8px;
      }

      .gallery-library-status {
        color: var(--gb-muted);
        font-size: .82rem;
        font-weight: 700;
        margin-top: 10px;
      }

      .gallery-library-modal-backdrop {
        align-items: center;
        background: rgba(12, 24, 17, .66);
        display: none;
        inset: 0;
        justify-content: center;
        padding: 24px;
        position: fixed;
        z-index: 1100;
      }

      .gallery-library-modal-backdrop.is-open {
        display: flex;
      }

      .gallery-library-modal {
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

      .gallery-library-modal-header,
      .gallery-library-modal-footer {
        align-items: center;
        display: flex;
        flex-shrink: 0;
        gap: 12px;
        justify-content: space-between;
        padding: 18px 20px;
      }

      .gallery-library-modal-header {
        border-bottom: 1px solid var(--gb-line);
      }

      .gallery-library-modal-header h2 {
        color: var(--gb-green);
        font-size: 1.08rem;
        font-weight: 800;
        margin: 0;
      }

      .gallery-library-modal-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        padding: 18px 20px;
      }

      .gallery-library-modal-footer {
        border-top: 1px solid var(--gb-line);
      }

      .gallery-library-close {
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

      .gallery-preview-tile,
      .gallery-existing-tile {
        aspect-ratio: 1;
        background: #f2f5f0;
        border: 1px solid var(--gb-line);
        border-radius: 8px;
        overflow: hidden;
        position: relative;
      }

      .gallery-preview-tile img,
      .gallery-existing-tile img,
      .gallery-cover-preview img {
        height: 100%;
        object-fit: cover;
        width: 100%;
      }

      .gallery-existing-tile label {
        align-items: center;
        background: rgba(16, 55, 32, .86);
        bottom: 0;
        color: #fff;
        display: flex;
        font-size: .75rem;
        font-weight: 800;
        gap: 6px;
        left: 0;
        margin: 0;
        padding: 8px;
        position: absolute;
        right: 0;
      }

      .gallery-existing-tile:has(input:checked) {
        border-color: #d92d20;
        opacity: .58;
      }

      .gallery-cover-preview {
        aspect-ratio: 16 / 10;
        background: #f2f5f0;
        border: 1px dashed #cbd6cf;
        border-radius: 8px;
        display: grid;
        margin-bottom: 14px;
        overflow: hidden;
        place-items: center;
      }

      .gallery-cover-placeholder {
        color: var(--gb-muted);
        font-size: .86rem;
        font-weight: 700;
        padding: 18px;
        text-align: center;
      }

      @media (max-width: 991.98px) {
        .gallery-side-panel {
          position: static;
        }

        .gallery-library-tools {
          grid-template-columns: 1fr;
        }

        .gallery-library-modal-backdrop {
          align-items: stretch;
          padding: 12px;
        }

        .gallery-library-modal {
          max-height: 96vh;
        }
      }
    </style>
  @endonce
@endpush

<div class="row g-3 gallery-form-grid">
  <div class="col-lg-8">
    <div class="admin-panel mb-3">
      <h2 class="gallery-panel-title"><i class="bi bi-card-text"></i> Album Details</h2>

      <div class="mb-3">
        <label class="admin-label" for="title">Title</label>
        <input id="title" name="title" value="{{ old('title', $album->title) }}" class="admin-control" required>
        @error('title') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <input type="hidden" name="slug" value="{{ old('slug', $album->slug) }}">

      <div class="mb-0">
        <label class="admin-label" for="description">Description</label>
        <textarea id="description" name="description" class="admin-textarea" style="min-height: 160px">{{ old('description', $album->description) }}</textarea>
        @error('description') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="admin-panel mb-3">
      <h2 class="gallery-panel-title"><i class="bi bi-images"></i> Album Photos</h2>

      <div class="mb-3">
        <label class="admin-label" for="images">Upload Photos</label>
        <label class="gallery-upload-zone" for="images">
          <i class="bi bi-cloud-arrow-up"></i>
          <span>
            <strong>Select album photos</strong>
            <span>JPG, PNG or WebP. Maximum 5MB each.</span>
          </span>
          <input id="images" type="file" name="images[]" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" multiple>
        </label>
        <div class="gallery-upload-summary" id="gallery-upload-summary">No new photos selected.</div>
        @error('images') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        @error('images.*') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="gallery-upload-preview mb-3" id="gallery-upload-preview"></div>

      <div class="border-top pt-3 mt-3">
        <div id="gallery-library-selected"></div>
        <div class="gallery-library-launch">
          <div>
            <h3 class="h6 fw-bold text-success mb-1">Media Library</h3>
            <p class="small text-muted mb-0" id="gallery-library-selected-summary">No Media Library images selected.</p>
          </div>
          <button class="admin-btn-secondary" type="button" id="gallery-library-open"><i class="bi bi-folder2-open"></i> Browse Library</button>
        </div>
      </div>

      @if($album->exists && $album->images->isNotEmpty())
        <h3 class="h6 fw-bold text-success mb-3">Current Photos</h3>
        <div class="gallery-existing-grid">
          @foreach($album->images as $image)
            <div class="gallery-existing-tile">
              <img src="{{ $image->url() }}" alt="{{ $image->alt_text ?: $album->title }}">
              <label>
                <input type="checkbox" name="delete_images[]" value="{{ $image->id }}">
                Remove
              </label>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  <div class="col-lg-4">
    <div class="admin-panel gallery-side-panel">
      <h2 class="gallery-panel-title"><i class="bi bi-sliders"></i> Publishing</h2>

      <div class="mb-3">
        <label class="admin-label" for="status">Status</label>
        <select id="status" name="status" class="admin-select">
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(old('status', $album->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="published_at">Publish Date</label>
        <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($album->published_at)->format('Y-m-d\TH:i')) }}" class="admin-control">
      </div>

      <div class="mb-3">
        <label class="admin-label" for="sort_order">Sort Order</label>
        <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $album->sort_order ?? 0) }}" class="admin-control">
      </div>

      <div class="mb-3">
        <label class="admin-label" for="cover_image">Cover Image</label>
        <label class="gallery-upload-zone" for="cover_image">
          <i class="bi bi-image"></i>
          <span>
            <strong>Select cover photo</strong>
            <span>Optional. Uses first album photo when empty.</span>
          </span>
          <input id="cover_image" type="file" name="cover_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        </label>
        @error('cover_image') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="gallery-cover-preview" id="gallery-cover-preview">
        @if($album->cover_image_path)
          <img src="{{ $album->coverUrl() }}" alt="{{ $album->title }}">
        @else
          <div class="gallery-cover-placeholder">Cover preview appears here.</div>
        @endif
      </div>
      <input type="hidden" id="cover_media_asset_id" name="cover_media_asset_id" value="{{ old('cover_media_asset_id', $album->cover_media_asset_id) }}">

      <button type="submit" class="admin-btn w-100"><i class="bi bi-check2"></i> Save Album</button>
      <a href="{{ route('admin.gallery.index') }}" class="admin-btn-secondary w-100 mt-2"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
  </div>
</div>

<div class="gallery-library-modal-backdrop" id="gallery-library-modal" aria-hidden="true">
  <div class="gallery-library-modal" role="dialog" aria-modal="true" aria-labelledby="gallery-library-title">
    <div class="gallery-library-modal-header">
      <h2 id="gallery-library-title"><i class="bi bi-images"></i> Media Library</h2>
      <button class="gallery-library-close" type="button" id="gallery-library-close" aria-label="Close Media Library">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
    <div class="gallery-library-modal-body">
      <div class="gallery-library-tools">
        <input id="gallery-library-search" class="admin-control" type="search" placeholder="Search media library">
        <button class="admin-btn-secondary" type="button" id="gallery-library-refresh"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
      </div>
      <div class="gallery-library-grid" id="gallery-library-grid"></div>
      <div class="gallery-library-status" id="gallery-library-status">Search or load the latest images.</div>
      <button type="button" class="admin-btn-secondary mt-2 w-100" id="gallery-library-more"><i class="bi bi-plus-lg"></i> Load More</button>
    </div>
    <div class="gallery-library-modal-footer">
      <span class="small text-muted" id="gallery-library-modal-summary">Selected images are added when you save the album.</span>
      <button class="admin-btn" type="button" id="gallery-library-done"><i class="bi bi-check2"></i> Done</button>
    </div>
  </div>
</div>

@push('scripts')
  @once
    <script>
      const galleryMediaUrl = @json(route('admin.editor.media.index'));
      let galleryLibraryCursor = null;
      let galleryLibraryHasMore = true;
      let galleryLibrarySearch = '';
      let galleryLibraryTimer = null;
      let galleryLibraryLoaded = false;
      const gallerySelectedIds = new Set();

      function escapeGalleryHtml(value) {
        return String(value || '').replace(/[&<>"']/g, (char) => ({
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#039;'
        }[char]));
      }

      function renderImagePreview(input, targetId, multiple) {
        const target = document.getElementById(targetId);

        if (!target) {
          return;
        }

        target.innerHTML = '';
        Array.from(input.files || []).forEach((file) => {
          const url = URL.createObjectURL(file);
          const tile = document.createElement('div');
          tile.className = multiple ? 'gallery-preview-tile' : '';
          tile.innerHTML = `<img src="${url}" alt="">`;
          target.appendChild(tile);
          tile.querySelector('img')?.addEventListener('load', () => URL.revokeObjectURL(url), { once: true });
        });
      }

      document.getElementById('images')?.addEventListener('change', function() {
        renderImagePreview(this, 'gallery-upload-preview', true);
        const summary = document.getElementById('gallery-upload-summary');
        const count = this.files?.length || 0;

        if (summary) {
          summary.textContent = count ? `${count} new ${count === 1 ? 'photo' : 'photos'} selected.` : 'No new photos selected.';
        }
      });

      document.getElementById('cover_image')?.addEventListener('change', function() {
        renderImagePreview(this, 'gallery-cover-preview', false);
        document.getElementById('cover_media_asset_id').value = '';
      });

      function syncGallerySelectedInputs() {
        const selected = document.getElementById('gallery-library-selected');

        if (!selected) {
          return;
        }

        selected.innerHTML = Array.from(gallerySelectedIds)
          .map((id) => `<input type="hidden" name="library_images[]" value="${id}">`)
          .join('');

        const count = gallerySelectedIds.size;
        const summary = document.getElementById('gallery-library-selected-summary');
        const modalSummary = document.getElementById('gallery-library-modal-summary');

        if (summary) {
          summary.textContent = count ? `${count} Media Library ${count === 1 ? 'image' : 'images'} selected.` : 'No Media Library images selected.';
        }

        if (modalSummary) {
          modalSummary.textContent = count ? `${count} selected. Save the album to attach them.` : 'Selected images are added when you save the album.';
        }
      }

      function addLibraryImage(media, button) {
        gallerySelectedIds.add(Number(media.id));
        button?.closest('.gallery-library-item')?.classList.add('is-selected');
        syncGallerySelectedInputs();

        const summary = document.getElementById('gallery-upload-summary');
        if (summary) {
          const count = gallerySelectedIds.size;
          summary.textContent = count ? `${count} Media Library ${count === 1 ? 'image' : 'images'} selected.` : summary.textContent;
        }
      }

      function setLibraryCover(media) {
        document.getElementById('cover_media_asset_id').value = media.id;
        document.getElementById('gallery-cover-preview').innerHTML = `<img src="${escapeGalleryHtml(media.url)}" alt="">`;
      }

      function renderGalleryLibraryItems(items, append = false) {
        const grid = document.getElementById('gallery-library-grid');

        if (!grid) {
          return;
        }

        if (!append) {
          grid.innerHTML = '';
        }

        if (!items.length && !append) {
          grid.innerHTML = '<div class="text-muted small">No library images found.</div>';
          return;
        }

        items.forEach((media) => {
          const item = document.createElement('div');
          item.className = `gallery-library-item${gallerySelectedIds.has(Number(media.id)) ? ' is-selected' : ''}`;
          item.innerHTML = `
            <img src="${escapeGalleryHtml(media.url)}" alt="">
            <span>${escapeGalleryHtml(media.name || 'Untitled image')}</span>
            <div class="gallery-library-actions">
              <button type="button" class="admin-btn-secondary" data-action="add">Add</button>
              <button type="button" class="admin-btn-secondary" data-action="cover">Cover</button>
            </div>
          `;
          item.querySelector('[data-action="add"]')?.addEventListener('click', () => addLibraryImage(media, item.querySelector('[data-action="add"]')));
          item.querySelector('[data-action="cover"]')?.addEventListener('click', () => setLibraryCover(media));
          grid.appendChild(item);
        });
      }

      async function loadGalleryLibrary({ reset = false } = {}) {
        const status = document.getElementById('gallery-library-status');
        const moreButton = document.getElementById('gallery-library-more');

        if (reset) {
          galleryLibraryCursor = null;
          galleryLibraryHasMore = true;
        }

        if (!galleryLibraryHasMore && !reset) {
          return;
        }

        if (status) {
          status.textContent = galleryLibraryCursor ? 'Loading more images...' : 'Loading latest images...';
        }

        if (moreButton) {
          moreButton.disabled = true;
        }

        try {
          const url = new URL(galleryMediaUrl, window.location.origin);
          url.searchParams.set('type', 'images');
          url.searchParams.set('per_page', '18');
          url.searchParams.set('cursor_mode', '1');

          if (galleryLibrarySearch) {
            url.searchParams.set('search', galleryLibrarySearch);
          }

          if (galleryLibraryCursor) {
            url.searchParams.set('cursor', galleryLibraryCursor.toString());
          }

          const response = await fetch(url, { headers: { Accept: 'application/json' } });

          if (!response.ok) {
            throw new Error('Media request failed');
          }

          const payload = await response.json();
          galleryLibraryLoaded = true;
          renderGalleryLibraryItems(payload.data || [], Boolean(galleryLibraryCursor));
          galleryLibraryHasMore = Boolean(payload.meta.has_more);
          galleryLibraryCursor = payload.meta.next_cursor || null;

          if (status) {
            const visibleCount = document.querySelectorAll('.gallery-library-item').length;
            status.textContent = galleryLibrarySearch
              ? `${visibleCount} matching ${visibleCount === 1 ? 'image' : 'images'} loaded. Refine the search to narrow a large library.`
              : `${visibleCount} latest ${visibleCount === 1 ? 'image' : 'images'} loaded. Use search to find older assets.`;
          }

          if (moreButton) {
            moreButton.hidden = !galleryLibraryHasMore;
            moreButton.disabled = false;
          }

        } catch (error) {
          if (status) {
            status.textContent = 'Could not load Media Library. Try again.';
          }

          if (moreButton) {
            moreButton.hidden = false;
            moreButton.disabled = false;
          }
        }
      }

      document.getElementById('gallery-library-search')?.addEventListener('input', (event) => {
        clearTimeout(galleryLibraryTimer);
        galleryLibrarySearch = event.target.value.trim();
        galleryLibraryTimer = setTimeout(() => loadGalleryLibrary({ reset: true }), 300);
      });

      document.getElementById('gallery-library-refresh')?.addEventListener('click', () => loadGalleryLibrary({ reset: true }));
      document.getElementById('gallery-library-more')?.addEventListener('click', () => loadGalleryLibrary());

      function openGalleryLibraryModal() {
        const modal = document.getElementById('gallery-library-modal');

        if (!modal) {
          return;
        }

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        document.getElementById('gallery-library-search')?.focus();

        if (!galleryLibraryLoaded) {
          loadGalleryLibrary({ reset: true });
        }
      }

      function closeGalleryLibraryModal() {
        const modal = document.getElementById('gallery-library-modal');

        if (!modal) {
          return;
        }

        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        document.getElementById('gallery-library-open')?.focus();
      }

      document.getElementById('gallery-library-open')?.addEventListener('click', openGalleryLibraryModal);
      document.getElementById('gallery-library-close')?.addEventListener('click', closeGalleryLibraryModal);
      document.getElementById('gallery-library-done')?.addEventListener('click', closeGalleryLibraryModal);
      document.getElementById('gallery-library-modal')?.addEventListener('click', (event) => {
        if (event.target.id === 'gallery-library-modal') {
          closeGalleryLibraryModal();
        }
      });
      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && document.getElementById('gallery-library-modal')?.classList.contains('is-open')) {
          closeGalleryLibraryModal();
        }
      });
    </script>
  @endonce
@endpush
