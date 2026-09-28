@csrf

<div class="row g-3">
  <div class="col-lg-8">
    <div class="admin-panel">
      <div class="mb-3">
        <label class="admin-label" for="question">Question</label>
        <input id="question" name="question" value="{{ old('question', $faq->question) }}" class="admin-control" required>
        @error('question') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="mb-0">
        <label class="admin-label" for="answer">Answer</label>
        <textarea id="answer" name="answer" class="admin-textarea" style="min-height: 260px" required>{{ old('answer', $faq->answer) }}</textarea>
        <p class="small text-muted mt-2 mb-0">Plain text is safest here. Basic links typed as full URLs can still be included in the copy.</p>
        @error('answer') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="admin-panel mb-3">
      <div class="mb-3">
        <label class="admin-label" for="category">Category</label>
        <select id="category" name="category" class="admin-select">
          @foreach(\App\Models\Faq::CATEGORIES as $key => $label)
            <option value="{{ $key }}" @selected(old('category', $faq->category ?: 'general') === $key)>{{ $label }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="status">Status</label>
        <select id="status" name="status" class="admin-select">
          @foreach(\App\Models\Faq::STATUSES as $status)
            <option value="{{ $status }}" @selected(old('status', $faq->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="published_at">Publish Date</label>
        <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($faq->published_at)->format('Y-m-d\TH:i')) }}" class="admin-control">
      </div>

      <div>
        <label class="admin-label" for="sort_order">Sort Order</label>
        <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $faq->sort_order ?? 0) }}" class="admin-control">
      </div>
    </div>

    <div class="d-grid gap-2">
      <button type="submit" class="admin-btn"><i class="bi bi-check2"></i> Save FAQ</button>
      <a href="{{ route('admin.faqs.index') }}" class="admin-btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
  </div>
</div>
