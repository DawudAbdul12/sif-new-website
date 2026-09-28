@csrf

<div class="row g-3">
  <div class="col-lg-8">
    <div class="admin-panel">
      <div class="mb-3">
        <label class="admin-label" for="name">Name</label>
        <input id="name" name="name" value="{{ old('name', $category->name) }}" class="admin-control" required>
        @error('name') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="admin-label" for="description">Description</label>
        <textarea id="description" name="description" class="admin-textarea" style="min-height: 220px">{{ old('description', $category->description) }}</textarea>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="admin-panel mb-3">
      <div class="mb-3">
        <label class="admin-label" for="slug">Slug</label>
        <input id="slug" name="slug" value="{{ old('slug', $category->slug) }}" class="admin-control" placeholder="auto-generated">
        @error('slug') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="admin-label" for="type">Post Scope</label>
        <select id="type" name="type" class="admin-select">
          @foreach(['all' => 'All posts', 'news' => 'News only', 'article' => 'Articles only'] as $type => $label)
            <option value="{{ $type }}" @selected(old('type', $category->type ?: 'all') === $type)>{{ $label }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="status">Status</label>
        <select id="status" name="status" class="admin-select">
          @foreach(['active', 'inactive'] as $status)
            <option value="{{ $status }}" @selected(old('status', $category->status ?: 'active') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="color">Accent Color</label>
        <input id="color" type="color" name="color" value="{{ old('color', $category->color ?: '#17472d') }}" class="admin-control" style="height: 48px; padding: 6px">
      </div>

      <div class="mb-3">
        <label class="admin-label" for="sort_order">Sort Order</label>
        <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="admin-control">
      </div>
    </div>

    <button type="submit" class="admin-btn w-100"><i class="bi bi-check2"></i> Save Category</button>
  </div>
</div>
