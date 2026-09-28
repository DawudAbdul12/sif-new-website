@extends('admin.layouts.app')

@section('title', 'New '.$groupLabel)
@section('page_title', 'New Profile')
@section('page_subtitle', 'Create a profile for '.$groupLabel.'.')

@section('content')
  <form method="POST" action="{{ route('admin.people.store', $group) }}" enctype="multipart/form-data">
    @include('admin.people._form')
  </form>
@endsection
