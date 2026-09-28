@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('page_title', 'Edit Category')
@section('page_subtitle', 'Refine category metadata and publishing scope.')

@section('content')
  <form method="POST" action="{{ route('admin.categories.update', $category) }}">
    @method('PUT')
    @include('admin.categories._form')
  </form>

  @include('admin.activity-logs._record-panel', ['recordActivitySubject' => $category])

  <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="mt-3" onsubmit="return confirm('Delete this category? Posts will be uncategorized.')">
    @csrf
    @method('DELETE')
    <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete Category</button>
  </form>
@endsection
