@extends('admin.layouts.app')

@section('title', 'Edit Document')
@section('page_title', 'Edit '.$typeLabel)
@section('page_subtitle', $document->title)

@section('content')
  <form method="POST" action="{{ route('admin.documents.update', [$type, $document]) }}" enctype="multipart/form-data">
    @method('PUT')
    @include('admin.documents._form')
  </form>

  @include('admin.activity-logs._record-panel', ['recordActivitySubject' => $document])

  <form method="POST" action="{{ route('admin.documents.destroy', [$type, $document]) }}" class="mt-3" onsubmit="return confirm('Delete this document?')">
    @csrf
    @method('DELETE')
    <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete Document</button>
  </form>
@endsection
