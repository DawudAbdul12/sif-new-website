@csrf

<div class="row g-3">
  <div class="col-lg-8">
    <div class="admin-panel">
      <div class="mb-3">
        <label class="admin-label" for="title">Title</label>
        <input id="title" name="title" value="{{ old('title', $notice->title) }}" class="admin-control" required>
        @error('title') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="admin-label" for="brief_description">Brief Description</label>
        <textarea id="brief_description" name="brief_description" class="admin-textarea" style="min-height: 120px">{{ old('brief_description', $notice->brief_description) }}</textarea>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="body">Notice Details</label>
        <textarea id="body" name="body" class="admin-textarea" style="min-height: 260px">{{ old('body', $notice->body) }}</textarea>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="admin-panel mb-3">
      <div class="mb-3">
        <label class="admin-label" for="slug">Slug</label>
        <input id="slug" name="slug" value="{{ old('slug', $notice->slug) }}" class="admin-control" placeholder="auto-generated">
        @error('slug') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="admin-label" for="status">Status</label>
        <select id="status" name="status" class="admin-select">
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(old('status', $notice->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="published_at">Publish Date</label>
        <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($notice->published_at)->format('Y-m-d\TH:i')) }}" class="admin-control">
      </div>

      <div class="mb-3">
        <label class="admin-label" for="expires_at">Expiry Date</label>
        <input id="expires_at" type="datetime-local" name="expires_at" value="{{ old('expires_at', optional($notice->expires_at)->format('Y-m-d\TH:i')) }}" class="admin-control">
      </div>
    </div>

    <div class="admin-panel mb-3">
      <label class="admin-label" for="file">Notice File</label>
      <input id="file" type="file" name="file" class="admin-control" @if(! $notice->exists) required @endif>
      <p class="small text-muted mt-2 mb-0">PDF, Word, PNG or JPG. Maximum 10MB.</p>
      @error('file') <div class="text-danger small mt-2">{{ $message }}</div> @enderror

      @if($notice->file_path)
        <div class="mt-3 p-3 rounded border bg-light">
          <div class="fw-bold">{{ $notice->file_name }}</div>
          <a href="{{ $notice->fileUrl() }}" target="_blank" rel="noopener" class="small">Open current file</a>
        </div>
      @endif
    </div>

    <button type="submit" class="admin-btn w-100"><i class="bi bi-check2"></i> Save Notice</button>
  </div>
</div>
