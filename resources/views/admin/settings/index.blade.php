@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page_title', 'Settings')
@section('page_subtitle', 'Manage global website configuration.')

@section('content')
  <form method="POST" action="{{ route('admin.settings.update') }}" class="admin-panel">
    @csrf
    @method('PUT')

    @foreach($settings as $group => $items)
      <h2 class="h5 fw-bold text-success mt-2">{{ ucfirst($group) }}</h2>
      <div class="row g-3 mb-4">
        @foreach($items as $setting)
          <div class="col-md-6">
            <label class="admin-label" for="setting_{{ $setting->key }}">{{ $setting->label }}</label>
            <textarea id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}]" class="admin-textarea" style="min-height:90px">{{ old("settings.{$setting->key}", $setting->value) }}</textarea>
          </div>
        @endforeach
      </div>
    @endforeach

    <button class="admin-btn" type="submit"><i class="bi bi-check2"></i> Save Settings</button>
  </form>

  @include('admin.activity-logs._record-panel', [
    'recordActivityHeading' => 'Settings Activity',
    'recordActivityType' => \App\Models\SiteSetting::class,
  ])
@endsection
