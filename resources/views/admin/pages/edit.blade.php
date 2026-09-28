@extends('admin.layouts.app')

@section('title', 'Edit Page')
@section('page_title', 'Edit Page')
@section('page_subtitle', $page->title)

@section('content')
  <form method="POST" action="{{ route('admin.pages.update', $page) }}">
    @method('PUT')
    @include('admin.pages._form')
  </form>

  @include('admin.activity-logs._record-panel', ['recordActivitySubject' => $page])

  <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" class="mt-3" onsubmit="return confirm('Delete this page?')">
    @csrf
    @method('DELETE')
    <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete Page</button>
  </form>
@endsection
