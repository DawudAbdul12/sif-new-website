@extends('admin.layouts.app')

@section('title', 'New Purchase Price')
@section('page_title', 'New Purchase Price')
@section('page_subtitle', 'Create the approved market price popup shown to website visitors.')

@section('content')
  <form method="POST" action="{{ route('admin.purchase-prices.store') }}">
    @include('admin.purchase-prices._form')
  </form>
@endsection
