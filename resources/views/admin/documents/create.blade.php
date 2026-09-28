@extends('admin.layouts.app')

@section('title', 'New '.$typeLabel)
@section('page_title', 'New '.$typeLabel)
@section('page_subtitle', 'Create a repository document with metadata and file attachment.')

@section('content')
  <form method="POST" action="{{ route('admin.documents.store', $type) }}" enctype="multipart/form-data">
    @include('admin.documents._form')
  </form>
@endsection
