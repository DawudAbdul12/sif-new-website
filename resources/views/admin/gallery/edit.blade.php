@extends('admin.layouts.app')

@section('title', 'Edit Gallery Album')
@section('page_title', 'Edit Gallery Album')
@section('page_subtitle', $album->title)

@section('content')
  <form method="POST" action="{{ route('admin.gallery.update', $album) }}" enctype="multipart/form-data">
    @method('PUT')
    @include('admin.gallery._form')
  </form>

  @include('admin.activity-logs._record-panel', ['recordActivitySubject' => $album])

  <form method="POST" action="{{ route('admin.gallery.destroy', $album) }}" class="mt-3" onsubmit="return confirm('Delete this gallery album?')">
    @csrf
    @method('DELETE')
    <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete Album</button>
  </form>
@endsection
