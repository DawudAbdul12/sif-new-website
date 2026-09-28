@csrf

<div class="row g-3">
  <div class="col-lg-8">
    <div class="admin-panel">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="admin-label" for="prefix">Prefix</label>
          <input id="prefix" name="prefix" value="{{ old('prefix', $impactMetric->prefix) }}" class="admin-control" placeholder="US$">
        </div>
        <div class="col-md-4">
          <label class="admin-label" for="value">Count Value</label>
          <input id="value" name="value" value="{{ old('value', $impactMetric->value) }}" class="admin-control" placeholder="83.5" required>
          @error('value') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
          <label class="admin-label" for="suffix">Suffix</label>
          <input id="suffix" name="suffix" value="{{ old('suffix', $impactMetric->suffix) }}" class="admin-control" placeholder="M+">
        </div>
        <div class="col-md-2">
          <label class="admin-label" for="sort_order">Order</label>
          <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $impactMetric->sort_order ?? 0) }}" class="admin-control">
        </div>
        <div class="col-12">
          <label class="admin-label" for="label">Label</label>
          <input id="label" name="label" value="{{ old('label', $impactMetric->label) }}" class="admin-control" required>
          @error('label') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>
        <div class="col-12">
          <label class="admin-label" for="note">Note</label>
          <input id="note" name="note" value="{{ old('note', $impactMetric->note) }}" class="admin-control" placeholder="Optional supporting note">
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="admin-panel mb-3">
      <div class="mb-3">
        <label class="admin-label" for="tier">Tier</label>
        <select id="tier" name="tier" class="admin-select">
          @foreach(\App\Models\ImpactMetric::TIERS as $key => $label)
            <option value="{{ $key }}" @selected(old('tier', $impactMetric->tier ?: 'primary') === $key)>{{ $label }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="status">Status</label>
        <select id="status" name="status" class="admin-select">
          @foreach(\App\Models\ImpactMetric::STATUSES as $status)
            <option value="{{ $status }}" @selected(old('status', $impactMetric->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="admin-label" for="published_at">Publish Date</label>
        <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($impactMetric->published_at)->format('Y-m-d\TH:i')) }}" class="admin-control">
      </div>
    </div>

    <div class="d-grid gap-2">
      <button type="submit" class="admin-btn"><i class="bi bi-check2"></i> Save Metric</button>
      <a href="{{ route('admin.impact-metrics.index') }}" class="admin-btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
  </div>
</div>
