@extends('admin.layouts.app')

@section('title', 'New Gallery Album')
@section('page_title', 'New Gallery Album')
@section('page_subtitle', 'Create a photo album for the public gallery.')

@section('content')
  <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
    @include('admin.gallery._form')
  </form>
@endsection
