@extends('admin.layouts.app')

@section('title', 'New Press Release')
@section('page_title', 'New Press Release')
@section('page_subtitle', 'Create an official release with an attached publication file.')

@section('content')
  <form method="POST" action="{{ route('admin.press-releases.store') }}" enctype="multipart/form-data">
    @include('admin.press-releases._form')
  </form>
@endsection
