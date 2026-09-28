@extends('admin.layouts.app')

@section('title', 'Purchase Prices')
@section('page_title', 'Purchase Prices')
@section('page_subtitle', 'Manage the approved local gold purchase price shown on the website.')

@section('content')
  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 280px" placeholder="Search purchase prices">
        <select name="status" class="admin-select" style="width: 180px">
          <option value="">All statuses</option>
          @foreach(['draft', 'review', 'published', 'archived'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
      </form>
      <a href="{{ route('admin.purchase-prices.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Price</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>LBMA PM</th>
          <th>Exchange</th>
          <th>Total / Pound</th>
          <th>Status</th>
          <th>Homepage</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($purchasePrices as $purchasePrice)
          <tr>
            <td>
              <div class="fw-bold">{{ $purchasePrice->title }}</div>
              <div class="small text-muted">{{ $purchasePrice->display_at?->format('D, jS M Y @ g:i A') ?? 'No display date' }}</div>
            </td>
            <td>{{ $purchasePrice->price_currency }} {{ number_format((float) $purchasePrice->lbma_pm_price, 2) }}</td>
            <td>{{ $purchasePrice->price_currency }} 1 = {{ rtrim(rtrim(number_format((float) $purchasePrice->exchange_rate, 4), '0'), '.') }}</td>
            <td class="fw-bold">{{ $purchasePrice->currency }} {{ number_format((float) $purchasePrice->total_price_per_pound) }}</td>
            <td><span class="admin-badge {{ $purchasePrice->status }}">{{ ucfirst($purchasePrice->status) }}</span></td>
            <td>
              @if($purchasePrice->show_on_home)
                <span class="admin-badge">Visible</span>
              @else
                <span class="text-muted small">Hidden</span>
              @endif
            </td>
            <td class="text-end"><a href="{{ route('admin.purchase-prices.edit', $purchasePrice) }}" class="admin-btn-secondary">Edit</a></td>
          </tr>
        @empty
          @include('admin.partials.empty-table', [
            'colspan' => 7,
            'icon' => 'bi-cash-coin',
            'title' => request()->query() ? 'No prices match your filters' : 'No purchase prices yet',
            'message' => request()->query() ? 'Try another title, date, or publishing status.' : 'Create approved gold purchase price records and choose which one appears on the homepage.',
            'actionLabel' => 'New Price',
            'actionUrl' => route('admin.purchase-prices.create'),
            'resetUrl' => route('admin.purchase-prices.index'),
          ])
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">{{ $purchasePrices->links() }}</div>
  </section>
@endsection
