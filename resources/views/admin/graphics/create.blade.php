@extends('admin.layouts.app')

@section('title', 'New Graphic')
@section('page_title', 'New Graphic')
@section('page_subtitle', 'Add artwork to the public graphics page.')

@section('content')
  <form method="POST" action="{{ route('admin.graphics.store') }}" enctype="multipart/form-data">
    @include('admin.graphics._form')
  </form>
@endsection
