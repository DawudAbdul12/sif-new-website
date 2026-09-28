@extends('admin.layouts.app')

@section('title', 'Edit Purchase Price')
@section('page_title', 'Edit Purchase Price')
@section('page_subtitle', 'Update market price values, visibility, and validity window.')

@section('content')
  <form method="POST" action="{{ route('admin.purchase-prices.update', $purchasePrice) }}">
    @method('PUT')
    @include('admin.purchase-prices._form')
  </form>

  @include('admin.activity-logs._record-panel', ['recordActivitySubject' => $purchasePrice])
@endsection
