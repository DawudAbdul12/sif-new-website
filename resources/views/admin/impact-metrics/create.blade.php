@extends('admin.layouts.app')

@section('title', 'New Impact Metric')
@section('page_title', 'New Impact Metric')
@section('page_subtitle', 'Create a homepage impact counter.')

@section('content')
  <form method="POST" action="{{ route('admin.impact-metrics.store') }}">
    @include('admin.impact-metrics._form')
  </form>
@endsection
