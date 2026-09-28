@extends('admin.layouts.app')

@section('title', 'Edit Post')
@section('page_title', 'Edit Post')
@section('page_subtitle', $post->title)

@section('content')
  <form method="POST" action="{{ route('admin.posts.update', $post) }}">
    @method('PUT')
    @include('admin.posts._form')
  </form>

  <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="mt-3" onsubmit="return confirm('Delete this post?')">
    @csrf
    @method('DELETE')
    <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete Post</button>
  </form>
@endsection
