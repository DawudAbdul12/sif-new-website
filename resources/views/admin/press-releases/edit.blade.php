@extends('admin.layouts.app')

@section('title', 'Edit Press Release')
@section('page_title', 'Edit Press Release')
@section('page_subtitle', $pressRelease->title)

@section('content')
  <form method="POST" action="{{ route('admin.press-releases.update', $pressRelease) }}" enctype="multipart/form-data">
    @method('PUT')
    @include('admin.press-releases._form')
  </form>

  @include('admin.activity-logs._record-panel', ['recordActivitySubject' => $pressRelease])

  <form method="POST" action="{{ route('admin.press-releases.destroy', $pressRelease) }}" class="mt-3" onsubmit="return confirm('Delete this press release?')">
    @csrf
    @method('DELETE')
    <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete Release</button>
  </form>
@endsection
