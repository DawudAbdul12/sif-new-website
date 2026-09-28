@extends('admin.layouts.app')

@section('title', 'Edit Profile')
@section('page_title', 'Edit Profile')
@section('page_subtitle', $person->name)

@section('content')
  <form method="POST" action="{{ route('admin.people.update', [$group, $person]) }}" enctype="multipart/form-data">
    @method('PUT')
    @include('admin.people._form')
  </form>

  @include('admin.activity-logs._record-panel', ['recordActivitySubject' => $person])

  <form method="POST" action="{{ route('admin.people.destroy', [$group, $person]) }}" class="mt-3" onsubmit="return confirm('Delete this profile?')">
    @csrf
    @method('DELETE')
    <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete Profile</button>
  </form>
@endsection
