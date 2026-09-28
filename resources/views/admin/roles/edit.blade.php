@extends('admin.layouts.app')

@section('title', 'Edit Role')
@section('page_title', 'Edit Role')
@section('page_subtitle', $role->name)

@section('content')
  <form method="POST" action="{{ route('admin.roles.update', $role) }}">
    @method('PUT')
    @include('admin.roles._form')
  </form>

  @include('admin.activity-logs._record-panel', ['recordActivitySubject' => $role])

  @unless($role->is_system)
    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="mt-3" onsubmit="return confirm('Delete this role?')">
      @csrf
      @method('DELETE')
      <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete Role</button>
    </form>
  @endunless
@endsection
