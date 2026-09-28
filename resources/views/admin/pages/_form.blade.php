@csrf

<div class="row g-3">
  <div class="col-lg-8">
    <div class="admin-panel">
      <div class="mb-3">
        <label class="admin-label" for="title">Title</label>
        <input id="title" name="title" value="{{ old('title', $page->title) }}" class="admin-control" required>
        @error('title') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="admin-label" for="excerpt">Excerpt</label>
        <textarea id="excerpt" name="excerpt" class="admin-textarea" style="min-height: 90px">{{ old('excerpt', $page->excerpt) }}</textarea>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="body">Body</label>
        <textarea id="body" name="body" class="admin-textarea" style="min-height: 360px">{{ old('body', $page->body) }}</textarea>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="admin-panel mb-3">
      <div class="mb-3">
        <label class="admin-label" for="slug">Slug</label>
        <input id="slug" name="slug" value="{{ old('slug', $page->slug) }}" class="admin-control" placeholder="auto-generated">
        @error('slug') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="admin-label" for="template">Template</label>
        <select id="template" name="template" class="admin-select">
          @foreach(['default', 'landing', 'policy', 'repository'] as $template)
            <option value="{{ $template }}" @selected(old('template', $page->template ?: 'default') === $template)>{{ ucfirst($template) }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="status">Status</label>
        <select id="status" name="status" class="admin-select">
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(old('status', $page->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="published_at">Publish Date</label>
        <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($page->published_at)->format('Y-m-d\TH:i')) }}" class="admin-control">
      </div>
    </div>

    <div class="admin-panel mb-3">
      <div class="mb-3">
        <label class="admin-label" for="seo_title">SEO Title</label>
        <input id="seo_title" name="seo_title" value="{{ old('seo_title', $page->seo_title) }}" class="admin-control">
      </div>
      <div class="mb-3">
        <label class="admin-label" for="seo_description">SEO Description</label>
        <textarea id="seo_description" name="seo_description" class="admin-textarea" style="min-height: 110px">{{ old('seo_description', $page->seo_description) }}</textarea>
      </div>
    </div>

    <button type="submit" class="admin-btn w-100"><i class="bi bi-check2"></i> Save Page</button>
  </div>
</div>
