@extends('admin.layouts.app')

@section('title', 'Upload Media')
@section('page_title', 'Upload Media')
@section('page_subtitle', 'Add files for use across the website.')

@section('content')
  <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="admin-panel">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="admin-label" for="file">File</label>
        <input id="file" type="file" name="file" class="admin-control" required>
        @error('file') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6">
        <label class="admin-label" for="name">Name</label>
        <input id="name" name="name" value="{{ old('name') }}" class="admin-control" required>
      </div>
      <div class="col-md-6">
        <label class="admin-label" for="alt_text">Alt Text</label>
        <input id="alt_text" name="alt_text" value="{{ old('alt_text') }}" class="admin-control">
      </div>
      <div class="col-12">
        <label class="admin-label" for="caption">Caption</label>
        <textarea id="caption" name="caption" class="admin-textarea" style="min-height:110px">{{ old('caption') }}</textarea>
      </div>
    </div>
    <button class="admin-btn mt-3" type="submit"><i class="bi bi-upload"></i> Upload</button>
  </form>
@endsection
