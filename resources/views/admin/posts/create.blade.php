@extends('admin.layouts.app')

@section('title', 'New Post')
@section('page_title', 'New Post')
@section('page_subtitle', 'Create a publishable update for the website.')

@section('content')
  <form method="POST" action="{{ route('admin.posts.store') }}">
    @include('admin.posts._form')
  </form>
@endsection
