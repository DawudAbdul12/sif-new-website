@extends('admin.layouts.app')

@section('title', 'New Registry Entry')
@section('page_title', 'New Registry Entry')
@section('page_subtitle', 'Add a licensed business manually.')

@section('content')
  <form method="POST" action="{{ route('admin.license-registry.store') }}">
    @include('admin.license-registry._form')
  </form>
@endsection
