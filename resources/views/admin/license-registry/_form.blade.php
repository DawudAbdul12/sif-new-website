@csrf

<div class="row g-3">
  <div class="col-lg-8">
    <div class="admin-panel">
      <div class="mb-3">
        <label class="admin-label" for="business_name">Registered Business Name</label>
        <input id="business_name" name="business_name" value="{{ old('business_name', $entry->business_name) }}" class="admin-control" required>
        @error('business_name') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="admin-label" for="certificate_number">License Certificate Number</label>
          <input id="certificate_number" name="certificate_number" value="{{ old('certificate_number', $entry->certificate_number) }}" class="admin-control" required>
          @error('certificate_number') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="admin-label" for="registry_number">SNO</label>
          <input id="registry_number" type="number" min="1" name="registry_number" value="{{ old('registry_number', $entry->registry_number) }}" class="admin-control">
          @error('registry_number') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="admin-label" for="issued_date">Issued Date</label>
          <input id="issued_date" type="date" name="issued_date" value="{{ old('issued_date', optional($entry->issued_date)->format('Y-m-d')) }}" class="admin-control">
          @error('issued_date') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="admin-label" for="expiry_date">Expiry Date</label>
          <input id="expiry_date" type="date" name="expiry_date" value="{{ old('expiry_date', optional($entry->expiry_date)->format('Y-m-d')) }}" class="admin-control">
          @error('expiry_date') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="mt-3">
        <label class="admin-label" for="notes">Notes</label>
        <textarea id="notes" name="notes" class="admin-textarea" style="min-height: 160px">{{ old('notes', $entry->notes) }}</textarea>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="admin-panel mb-3">
      <div class="mb-3">
        <label class="admin-label" for="category">License Category</label>
        <select id="category" name="category" class="admin-select" required>
          @foreach($categories as $key => $label)
            <option value="{{ $key }}" @selected(old('category', $entry->category ?: 'buyerTier2') === $key)>{{ $label }}</option>
          @endforeach
        </select>
        @error('category') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="admin-label" for="status">Status</label>
        <select id="status" name="status" class="admin-select">
          @foreach(['active', 'inactive'] as $status)
            <option value="{{ $status }}" @selected(old('status', $entry->status ?: 'active') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <button type="submit" class="admin-btn w-100"><i class="bi bi-check2"></i> Save Registry Entry</button>

    @if($entry->exists)
      <form method="POST" action="{{ route('admin.license-registry.destroy', $entry) }}" class="mt-2" onsubmit="return confirm('Delete this registry entry?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="admin-danger w-100"><i class="bi bi-trash"></i> Delete Entry</button>
      </form>
    @endif
  </div>
</div>
