@extends('admin.layouts.app')

@section('title', 'New FAQ')
@section('page_title', 'New FAQ')
@section('page_subtitle', 'Create a frequently asked question for the resources page.')

@section('content')
  <form method="POST" action="{{ route('admin.faqs.store') }}">
    @include('admin.faqs._form')
  </form>
@endsection
