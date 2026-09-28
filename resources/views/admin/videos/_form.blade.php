@csrf

@push('styles')
  @once
    <style>
      .video-form-grid {
        align-items: start;
      }

      .video-form-section + .video-form-section {
        margin-top: 16px;
      }

      .video-panel-title {
        align-items: center;
        color: var(--gb-green);
        display: flex;
        font-size: 1rem;
        font-weight: 800;
        gap: 8px;
        margin: 0 0 18px;
      }

      .video-preview-frame {
        aspect-ratio: 16 / 9;
        background: #102217;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
      }

      .video-preview-frame iframe,
      .video-preview-frame img,
      .video-preview-frame .video-preview-placeholder {
        border: 0;
        height: 100%;
        object-fit: cover;
        width: 100%;
      }

      .video-preview-placeholder {
        align-items: center;
        color: rgba(255, 255, 255, 0.72);
        display: flex;
        flex-direction: column;
        font-weight: 700;
        gap: 10px;
        height: 100%;
        justify-content: center;
        padding: 20px;
        text-align: center;
      }

      .video-preview-placeholder.is-error {
        color: #ffd7d7;
      }

      .video-preview-placeholder i {
        color: var(--gb-gold);
        font-size: 2rem;
      }

      .video-save-panel {
        position: sticky;
        top: 24px;
      }

      .video-meta-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: 1fr;
      }

      .video-thumbnail-preview {
        aspect-ratio: 16 / 9;
        background: #f2f5f0;
        border: 1px dashed #cbd6cf;
        border-radius: 8px;
        display: grid;
        overflow: hidden;
        place-items: center;
      }

      .video-thumbnail-preview img {
        height: 100%;
        object-fit: cover;
        width: 100%;
      }

      .video-thumbnail-placeholder {
        color: var(--gb-muted);
        font-size: 0.86rem;
        font-weight: 700;
        padding: 18px;
        text-align: center;
      }

      @media (max-width: 991.98px) {
        .video-save-panel {
          position: static;
        }

        .video-meta-grid {
          grid-template-columns: 1fr;
        }
      }
    </style>
  @endonce
@endpush

<div class="row g-3 video-form-grid">
  <div class="col-lg-8">
    <div class="admin-panel video-form-section">
      <h2 class="video-panel-title"><i class="bi bi-play-circle"></i> Video Details</h2>

      <div class="mb-3">
        <label class="admin-label" for="title">Title</label>
        <input id="title" name="title" value="{{ old('title', $video->title) }}" class="admin-control" required>
        @error('title') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="admin-label" for="video_url">Video URL</label>
        <input id="video_url" type="url" name="video_url" value="{{ old('video_url', $video->video_url) }}" class="admin-control" placeholder="https://www.youtube.com/watch?v=..." required>
        <p class="small text-muted mt-2 mb-0">YouTube, YouTube Shorts, Vimeo, or an embeddable video URL.</p>
        @error('video_url') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="admin-label" for="description">Description</label>
        <textarea id="description" name="description" class="admin-textarea" style="min-height: 180px">{{ old('description', $video->description) }}</textarea>
        @error('description') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="admin-panel video-form-section">
      <h2 class="video-panel-title"><i class="bi bi-display"></i> Embed Preview</h2>
      <div class="video-preview-frame" id="video-embed-preview" data-saved-embed="{{ $video->embed_url }}">
        <div class="video-preview-placeholder">
          <i class="bi bi-play-btn"></i>
          <span>Paste a supported video URL to preview it here.</span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="admin-panel mb-3 video-save-panel">
      <h2 class="video-panel-title"><i class="bi bi-sliders"></i> Publishing</h2>

      <input type="hidden" name="slug" value="{{ old('slug', $video->slug) }}">

      <div class="mb-3">
        <label class="admin-label" for="status">Status</label>
        <select id="status" name="status" class="admin-select">
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(old('status', $video->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
      </div>

      <div class="video-meta-grid mb-3">
        <div>
          <label class="admin-label" for="published_at">Publish Date</label>
          <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($video->published_at)->format('Y-m-d\TH:i')) }}" class="admin-control">
        </div>

        <div>
          <label class="admin-label" for="sort_order">Sort Order</label>
          <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $video->sort_order ?? 0) }}" class="admin-control">
        </div>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="thumbnail">Thumbnail</label>
        <input id="thumbnail" type="file" name="thumbnail" class="admin-control" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        <p class="small text-muted mt-2 mb-0">JPG, PNG or WebP. Maximum 5MB.</p>
        @error('thumbnail') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="video-thumbnail-preview mb-3" id="video-thumbnail-preview">
        @if($video->thumbnail_path)
          <img src="{{ $video->thumbnailUrl() }}" alt="{{ $video->title }}">
        @else
          <div class="video-thumbnail-placeholder">Default thumbnail will be used.</div>
        @endif
      </div>

      @if($video->exists)
        <div class="p-3 rounded border bg-light mb-3">
          <div class="small text-muted">Public embed URL</div>
          <div class="small fw-bold text-break">{{ $video->embed_url }}</div>
        </div>
      @endif

      <button type="submit" class="admin-btn w-100"><i class="bi bi-check2"></i> Save Video</button>
      <a href="{{ route('admin.videos.index') }}" class="admin-btn-secondary w-100 mt-2"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
  </div>
</div>

@push('scripts')
  @once
    <script>
      function sifVideoEmbedUrl(value) {
        if (!value) {
          return '';
        }

        try {
          const url = new URL(value);
          const host = url.hostname.toLowerCase();
          const path = url.pathname.replace(/^\/+|\/+$/g, '');

          if (host.includes('youtube.com')) {
            if (path.startsWith('embed/')) {
              return url.href;
            }

            if (path.startsWith('shorts/')) {
              return `https://www.youtube.com/embed/${path.replace('shorts/', '')}`;
            }

            const id = url.searchParams.get('v');

            if (id) {
              return `https://www.youtube.com/embed/${id}`;
            }
          }

          if (host.includes('youtu.be') && path) {
            return `https://www.youtube.com/embed/${path}`;
          }

          if (host.includes('vimeo.com')) {
            if (host.includes('player.vimeo.com')) {
              return url.href;
            }

            const id = path.split('/').filter(Boolean).pop();

            if (id) {
              return `https://player.vimeo.com/video/${id}`;
            }
          }

          return url.href;
        } catch (error) {
          return '';
        }
      }

      function updateVideoPreview() {
        const input = document.getElementById('video_url');
        const preview = document.getElementById('video-embed-preview');

        if (!input || !preview) {
          return;
        }

        const embedUrl = sifVideoEmbedUrl(input.value) || preview.dataset.savedEmbed || '';

        if (!input.value.trim() && !preview.dataset.savedEmbed) {
          preview.innerHTML = '<div class="video-preview-placeholder"><i class="bi bi-play-btn"></i><span>Paste a supported video URL to preview it here.</span></div>';
          return;
        }

        if (!embedUrl) {
          preview.innerHTML = '<div class="video-preview-placeholder is-error"><i class="bi bi-exclamation-triangle"></i><span>Enter a valid video URL.</span></div>';
          return;
        }

        const currentFrame = preview.querySelector('iframe');
        if (currentFrame?.src === embedUrl) {
          return;
        }

        preview.innerHTML = '';

        const iframe = document.createElement('iframe');
        iframe.src = embedUrl;
        iframe.title = 'Video preview';
        iframe.allow = 'autoplay; encrypted-media; picture-in-picture';
        iframe.allowFullscreen = true;
        preview.appendChild(iframe);
      }

      document.getElementById('video_url')?.addEventListener('input', updateVideoPreview);
      document.getElementById('video_url')?.addEventListener('change', updateVideoPreview);
      updateVideoPreview();

      document.getElementById('thumbnail')?.addEventListener('change', function(event) {
        const file = event.target.files?.[0];
        const preview = document.getElementById('video-thumbnail-preview');

        if (!file || !preview) {
          return;
        }

        const url = URL.createObjectURL(file);
        preview.innerHTML = `<img src="${url}" alt="">`;
        preview.querySelector('img')?.addEventListener('load', () => URL.revokeObjectURL(url), { once: true });
      });
    </script>
  @endonce
@endpush
