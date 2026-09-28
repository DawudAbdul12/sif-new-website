@extends('admin.layouts.app')

@section('title', 'Edit Project')
@section('page_title', 'Edit Project')
@section('page_subtitle', $project->full_name)

@section('content')
  <form method="POST" action="{{ route('admin.projects.update', $project) }}">
    @method('PUT')
    @include('admin.projects._form')
  </form>

  @include('admin.activity-logs._record-panel', [
    'recordActivitySubject' => $project,
    'recordActivityLogs' => $recordActivityLogs,
  ])

  <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" class="mt-3" onsubmit="return confirm('Delete this project?')">
    @csrf
    @method('DELETE')
    <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete Project</button>
  </form>
@endsection
