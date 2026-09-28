@extends('admin.layouts.app')

@section('title', 'Edit Notice')
@section('page_title', 'Edit Notice')
@section('page_subtitle', $notice->title)

@section('content')
  <form method="POST" action="{{ route('admin.notices.update', $notice) }}" enctype="multipart/form-data">
    @method('PUT')
    @include('admin.notices._form')
  </form>

  @include('admin.activity-logs._record-panel', ['recordActivitySubject' => $notice])

  <form method="POST" action="{{ route('admin.notices.destroy', $notice) }}" class="mt-3" onsubmit="return confirm('Delete this notice?')">
    @csrf
    @method('DELETE')
    <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete Notice</button>
  </form>
@endsection
