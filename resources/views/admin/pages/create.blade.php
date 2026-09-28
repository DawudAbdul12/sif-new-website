@extends('admin.layouts.app')

@section('title', 'New Page')
@section('page_title', 'New Page')
@section('page_subtitle', 'Compose a CMS-managed website page.')

@section('content')
  <form method="POST" action="{{ route('admin.pages.store') }}">
    @include('admin.pages._form')
  </form>
@endsection
