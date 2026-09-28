@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('page_title', 'Edit User')
@section('page_subtitle', $user->name)

@section('content')
  <form method="POST" action="{{ route('admin.users.update', $user) }}">
    @method('PUT')
    @include('admin.users._form')
  </form>

  @include('admin.activity-logs._record-panel', ['recordActivitySubject' => $user])

  @unless(auth()->user()->is($user))
    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="mt-3" onsubmit="return confirm('Delete this user?')">
      @csrf
      @method('DELETE')
      <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete User</button>
    </form>
  @endunless
@endsection
