@extends('admin.layouts.app')

@section('title', 'Impact Metrics')
@section('page_title', 'Impact Metrics')
@section('page_subtitle', 'Manage the counters shown in the homepage impact section.')

@section('content')
  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 260px" placeholder="Search impact metrics">
        <select name="tier" class="admin-select" style="width: 160px">
          <option value="">All tiers</option>
          @foreach(\App\Models\ImpactMetric::TIERS as $key => $label)
            <option value="{{ $key }}" @selected(request('tier') === $key)>{{ $label }}</option>
          @endforeach
        </select>
        <select name="status" class="admin-select" style="width: 160px">
          <option value="">All statuses</option>
          @foreach(\App\Models\ImpactMetric::STATUSES as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
      </form>
      <a href="{{ route('admin.impact-metrics.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Metric</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Metric</th>
          <th>Tier</th>
          <th>Status</th>
          <th>Order</th>
          <th>Published</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($metrics as $metric)
          <tr>
            <td>
              <div class="fw-bold">{{ $metric->prefix }}{{ $metric->value }}{{ $metric->suffix }}</div>
              <div>{{ $metric->label }}</div>
              @if($metric->note)
                <div class="small text-muted">{{ $metric->note }}</div>
              @endif
            </td>
            <td>{{ \App\Models\ImpactMetric::TIERS[$metric->tier] ?? ucfirst($metric->tier) }}</td>
            <td><span class="admin-badge {{ $metric->status }}">{{ ucfirst($metric->status) }}</span></td>
            <td>{{ $metric->sort_order }}</td>
            <td>{{ $metric->published_at?->format('M j, Y') ?? 'Not published' }}</td>
            <td class="text-end"><a href="{{ route('admin.impact-metrics.edit', $metric) }}" class="admin-btn-secondary">Edit</a></td>
          </tr>
        @empty
          @include('admin.partials.empty-table', [
            'colspan' => 6,
            'icon' => 'bi-bar-chart-line',
            'title' => request()->query() ? 'No metrics match your filters' : 'No impact metrics yet',
            'message' => request()->query() ? 'Try another metric, tier, or status.' : 'Create metrics to populate the homepage impact section.',
            'actionLabel' => 'New Metric',
            'actionUrl' => route('admin.impact-metrics.create'),
            'resetUrl' => route('admin.impact-metrics.index'),
          ])
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">{{ $metrics->links() }}</div>
  </section>
@endsection
