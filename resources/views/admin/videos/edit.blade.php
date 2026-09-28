@extends('admin.layouts.app')

@section('title', 'Edit Video')
@section('page_title', 'Edit Video')
@section('page_subtitle', $video->title)

@section('content')
  <form method="POST" action="{{ route('admin.videos.update', $video) }}" enctype="multipart/form-data">
    @method('PUT')
    @include('admin.videos._form')
  </form>

  @include('admin.activity-logs._record-panel', ['recordActivitySubject' => $video])

  <form method="POST" action="{{ route('admin.videos.destroy', $video) }}" class="mt-3" onsubmit="return confirm('Delete this video?')">
    @csrf
    @method('DELETE')
    <button class="admin-btn-secondary text-danger" type="submit"><i class="bi bi-trash"></i> Delete Video</button>
  </form>
@endsection
