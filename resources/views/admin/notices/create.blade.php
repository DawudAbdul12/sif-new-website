@extends('admin.layouts.app')

@section('title', 'New Notice')
@section('page_title', 'New Notice')
@section('page_subtitle', 'Create a time-sensitive notice with an attached file.')

@section('content')
  <form method="POST" action="{{ route('admin.notices.store') }}" enctype="multipart/form-data">
    @include('admin.notices._form')
  </form>
@endsection
