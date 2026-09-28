@extends('admin.layouts.app')

@section('title', 'Edit Media')
@section('page_title', 'Edit Media')
@section('page_subtitle', $asset->name)

@section('content')
  <div class="row g-3">
    <div class="col-lg-5">
      <div class="admin-panel">
        @if(str_starts_with((string) $asset->mime_type, 'image/'))
          <img src="{{ $asset->url() }}" alt="{{ $asset->alt_text }}" style="width:100%;border-radius:6px;border:1px solid #edf1ee;">
        @else
          <p class="mb-0"><a href="{{ $asset->url() }}" target="_blank" rel="noopener">Open file</a></p>
        @endif
      </div>
    </div>
    <div class="col-lg-7">
      <form method="POST" action="{{ route('admin.media.update', $asset) }}" class="admin-panel">
        @csrf
        @method('PUT')
        <div class="mb-3">
          <label class="admin-label" for="name">Name</label>
          <input id="name" name="name" value="{{ old('name', $asset->name) }}" class="admin-control" required>
        </div>
        <div class="mb-3">
          <label class="admin-label" for="alt_text">Alt Text</label>
          <input id="alt_text" name="alt_text" value="{{ old('alt_text', $asset->alt_text) }}" class="admin-control">
        </div>
        <div class="mb-3">
          <label class="admin-label" for="caption">Caption</label>
          <textarea id="caption" name="caption" class="admin-textarea" style="min-height:110px">{{ old('caption', $asset->caption) }}</textarea>
        </div>
        <button class="admin-btn" type="submit"><i class="bi bi-check2"></i> Save Media</button>
      </form>

      @include('admin.activity-logs._record-panel', ['recordActivitySubject' => $asset])

      <form method="POST" action="{{ route('admin.media.destroy', $asset) }}" class="mt-3" onsubmit="return confirm('Delete this media file?')">
        @csrf
        @method('DELETE')
        <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete Media</button>
      </form>
    </div>
  </div>
@endsection
