@extends('admin.layouts.app')

@section('title', 'Edit FAQ')
@section('page_title', 'Edit FAQ')
@section('page_subtitle', $faq->question)

@section('content')
  <form method="POST" action="{{ route('admin.faqs.update', $faq) }}">
    @method('PUT')
    @include('admin.faqs._form')
  </form>

  @include('admin.activity-logs._record-panel', [
    'recordActivitySubject' => $faq,
    'recordActivityLogs' => $recordActivityLogs,
  ])

  <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" class="mt-3" onsubmit="return confirm('Delete this FAQ?')">
    @csrf
    @method('DELETE')
    <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete FAQ</button>
  </form>
@endsection
