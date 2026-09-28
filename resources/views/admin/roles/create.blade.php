@extends('admin.layouts.app')

@section('title', 'New Role')
@section('page_title', 'New Role')
@section('page_subtitle', 'Create a permission set for CMS users.')

@section('content')
  <form method="POST" action="{{ route('admin.roles.store') }}">
    @include('admin.roles._form')
  </form>
@endsection
