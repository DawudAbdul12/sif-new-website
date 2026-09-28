@extends('admin.layouts.app')

@section('title', 'New Category')
@section('page_title', 'New Category')
@section('page_subtitle', 'Create a publishing category for posts.')

@section('content')
  <form method="POST" action="{{ route('admin.categories.store') }}">
    @include('admin.categories._form')
  </form>
@endsection
