@csrf

<div class="row g-3">
  <div class="col-lg-8">
    <div class="admin-panel">
      <div class="mb-3">
        <label class="admin-label" for="title">Title</label>
        <input id="title" name="title" value="{{ old('title', $document->title) }}" class="admin-control" required>
        @error('title') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="admin-label" for="brief_description">Brief Description</label>
        <textarea id="brief_description" name="brief_description" class="admin-textarea" style="min-height: 130px">{{ old('brief_description', $document->brief_description) }}</textarea>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="admin-label" for="fiscal_year">Fiscal / Report Year</label>
          <input id="fiscal_year" name="fiscal_year" value="{{ old('fiscal_year', $document->fiscal_year) }}" class="admin-control" placeholder="2026">
        </div>
        <div class="col-md-6">
          <label class="admin-label" for="document_date">Document Date</label>
          <input id="document_date" type="date" name="document_date" value="{{ old('document_date', optional($document->document_date)->format('Y-m-d')) }}" class="admin-control">
        </div>
      </div>

      <div class="mt-3">
        <label class="admin-label" for="counterparty">Counterparty / Entity</label>
        <input id="counterparty" name="counterparty" value="{{ old('counterparty', $document->counterparty) }}" class="admin-control" placeholder="Useful for contracts or entity-specific reports">
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="admin-panel mb-3">
      <div class="mb-3">
        <label class="admin-label" for="slug">Slug</label>
        <input id="slug" name="slug" value="{{ old('slug', $document->slug) }}" class="admin-control" placeholder="auto-generated">
        @error('slug') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="admin-label" for="status">Status</label>
        <select id="status" name="status" class="admin-select">
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(old('status', $document->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="published_at">Publish Date</label>
        <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($document->published_at)->format('Y-m-d\TH:i')) }}" class="admin-control">
      </div>

      @if($type !== 'contracts')
        <div class="mb-0">
          <label class="admin-label" for="sort_order">Sort Order</label>
          <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $document->sort_order ?? 0) }}" class="admin-control">
        </div>
      @endif
    </div>

    <div class="admin-panel mb-3">
      <label class="admin-label" for="file">Document File</label>
      <input id="file" type="file" name="file" class="admin-control" @if(! $document->exists) required @endif>
      <p class="small text-muted mt-2 mb-0">PDF, Word, Excel, PNG or JPG. Maximum 20MB.</p>
      @error('file') <div class="text-danger small mt-2">{{ $message }}</div> @enderror

      @if($document->file_path)
        <div class="mt-3 p-3 rounded border bg-light">
          <div class="fw-bold">{{ $document->file_name }}</div>
          <a href="{{ $document->fileUrl() }}" target="_blank" rel="noopener" class="small">Open current file</a>
        </div>
      @endif
    </div>

    <div class="admin-panel mb-3">
      <label class="admin-label" for="cover_image">Cover Image</label>
      <input id="cover_image" type="file" name="cover_image" class="admin-control" accept="image/*">
      <p class="small text-muted mt-2 mb-0">Optional visual preview for report cards.</p>
      @error('cover_image') <div class="text-danger small mt-2">{{ $message }}</div> @enderror

      @if($document->cover_image_path)
        <img src="{{ $document->coverImageUrl() }}" alt="" class="mt-3" style="width:100%;border-radius:6px;border:1px solid #edf1ee;">
      @endif
    </div>

    <button type="submit" class="admin-btn w-100"><i class="bi bi-check2"></i> Save Document</button>
  </div>
</div>
