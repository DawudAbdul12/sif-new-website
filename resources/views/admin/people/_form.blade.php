@csrf

<div class="row g-3">
  <div class="col-lg-8">
    <div class="admin-panel">
      <div class="row g-3">
        <div class="col-md-8">
          <label class="admin-label" for="name">Full Name</label>
          <input id="name" name="name" value="{{ old('name', $person->name) }}" class="admin-control" required>
          @error('name') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4">
          <label class="admin-label" for="slug">Slug</label>
          <input id="slug" name="slug" value="{{ old('slug', $person->slug) }}" class="admin-control" placeholder="auto-generated">
          @error('slug') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="row g-3 mt-1">
        <div class="col-md-6">
          <label class="admin-label" for="position">Position / Role</label>
          <input id="position" name="position" value="{{ old('position', $person->position) }}" class="admin-control" placeholder="Chief Executive Officer">
        </div>
        <div class="col-md-6">
          <label class="admin-label" for="department">Department / Portfolio</label>
          <input id="department" name="department" value="{{ old('department', $person->department) }}" class="admin-control">
        </div>
      </div>

      <div class="mt-3">
        <label class="admin-label" for="appointment_type">Appointment Type</label>
        <input id="appointment_type" name="appointment_type" value="{{ old('appointment_type', $person->appointment_type) }}" class="admin-control" placeholder="Chairperson, Executive, Representative, Director">
      </div>

      <div class="mt-3">
        <label class="admin-label" for="brief_profile">Brief Profile</label>
        <textarea id="brief_profile" name="brief_profile" class="admin-textarea" style="min-height: 120px">{{ old('brief_profile', $person->brief_profile) }}</textarea>
      </div>

      <div class="mt-3">
        <label class="admin-label" for="bio">Full Biography</label>
        <textarea id="bio" name="bio" class="admin-textarea" style="min-height: 260px">{{ old('bio', $person->bio) }}</textarea>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="admin-panel mb-3">
      <label class="admin-label" for="photo">Profile Photo</label>
      <input id="photo" type="file" name="photo" class="admin-control" accept=".jpg,.jpeg,.png,.webp">
      <p class="small text-muted mt-2 mb-0">JPG, PNG or WEBP. Maximum 5MB.</p>
      @error('photo') <div class="text-danger small mt-2">{{ $message }}</div> @enderror

      <img src="{{ $person->photoUrl() }}" alt="" class="mt-3" style="width:100%;aspect-ratio:4/5;object-fit:cover;border-radius:6px;border:1px solid #edf1ee;">
    </div>

    <div class="admin-panel mb-3">
      <div class="mb-3">
        <label class="admin-label" for="status">Status</label>
        <select id="status" name="status" class="admin-select">
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(old('status', $person->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="admin-label" for="published_at">Publish Date</label>
        <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($person->published_at)->format('Y-m-d\TH:i')) }}" class="admin-control">
      </div>

      <div class="mb-3">
        <label class="admin-label" for="sort_order">Sort Order</label>
        <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $person->sort_order ?? 0) }}" class="admin-control">
      </div>

      @if($group === 'board')
        <div class="form-check">
          <input id="show_seal" type="checkbox" name="show_seal" value="1" class="form-check-input" @checked(old('show_seal', $person->show_seal))>
          <label for="show_seal" class="form-check-label fw-bold text-success">Show SIF seal on card</label>
        </div>
      @endif
    </div>

    <div class="admin-panel mb-3">
      <div class="mb-3">
        <label class="admin-label" for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $person->email) }}" class="admin-control">
      </div>
      <div class="mb-3">
        <label class="admin-label" for="phone">Phone</label>
        <input id="phone" name="phone" value="{{ old('phone', $person->phone) }}" class="admin-control">
      </div>
      <div class="mb-0">
        <label class="admin-label" for="linkedin_url">LinkedIn URL</label>
        <input id="linkedin_url" type="url" name="linkedin_url" value="{{ old('linkedin_url', $person->linkedin_url) }}" class="admin-control">
      </div>
    </div>

    <button type="submit" class="admin-btn w-100"><i class="bi bi-check2"></i> Save Profile</button>
  </div>
</div>
