@extends('admin.layouts.app')

@section('title', 'Edit Registry Entry')
@section('page_title', 'Edit Registry Entry')
@section('page_subtitle', 'Update registry details for a licensed business.')

@section('content')
  <form method="POST" action="{{ route('admin.license-registry.update', $entry) }}">
    @method('PUT')
    @include('admin.license-registry._form')
  </form>

  @include('admin.activity-logs._record-panel', ['recordActivitySubject' => $entry])
@endsection
