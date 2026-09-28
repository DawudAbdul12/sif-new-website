@extends('admin.layouts.app')

@section('title', 'Edit Graphic')
@section('page_title', 'Edit Graphic')
@section('page_subtitle', $graphic->title)

@section('content')
  <form method="POST" action="{{ route('admin.graphics.update', $graphic) }}" enctype="multipart/form-data">
    @method('PUT')
    @include('admin.graphics._form')
  </form>

  @include('admin.activity-logs._record-panel', ['recordActivitySubject' => $graphic])

  <form method="POST" action="{{ route('admin.graphics.destroy', $graphic) }}" class="mt-3" onsubmit="return confirm('Delete this graphic?')">
    @csrf
    @method('DELETE')
    <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete Graphic</button>
  </form>
@endsection
