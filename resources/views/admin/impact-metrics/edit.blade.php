@extends('admin.layouts.app')

@section('title', 'Edit Impact Metric')
@section('page_title', 'Edit Impact Metric')
@section('page_subtitle', $impactMetric->label)

@section('content')
  <form method="POST" action="{{ route('admin.impact-metrics.update', $impactMetric) }}">
    @method('PUT')
    @include('admin.impact-metrics._form')
  </form>

  @include('admin.activity-logs._record-panel', [
    'recordActivitySubject' => $impactMetric,
    'recordActivityLogs' => $recordActivityLogs,
  ])

  <form method="POST" action="{{ route('admin.impact-metrics.destroy', $impactMetric) }}" class="mt-3" onsubmit="return confirm('Delete this impact metric?')">
    @csrf
    @method('DELETE')
    <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete Metric</button>
  </form>
@endsection
