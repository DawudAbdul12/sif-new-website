@extends('admin.layouts.app')

@section('title', 'New User')
@section('page_title', 'New User')
@section('page_subtitle', 'Create a CMS account.')

@section('content')
  <form method="POST" action="{{ route('admin.users.store') }}">
    @include('admin.users._form')
  </form>
@endsection
