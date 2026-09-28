@extends('admin.layouts.app')

@section('title', 'New Project')
@section('page_title', 'New Project')
@section('page_subtitle', 'Create a project for the public project grid, detail page, filters, and map.')

@section('content')
  <form method="POST" action="{{ route('admin.projects.store') }}">
    @include('admin.projects._form')
  </form>
@endsection
