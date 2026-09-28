@extends('admin.layouts.app')

@section('title', 'New Video')
@section('page_title', 'New Video')
@section('page_subtitle', 'Add a public media video.')

@section('content')
  <form method="POST" action="{{ route('admin.videos.store') }}" enctype="multipart/form-data">
    @include('admin.videos._form')
  </form>
@endsection
