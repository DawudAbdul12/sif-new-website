@extends('admin.layouts.app')

@section('title', 'FAQs')
@section('page_title', 'FAQs')
@section('page_subtitle', 'Manage the frequently asked questions shown on the resources page.')

@section('content')
  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 260px" placeholder="Search FAQs">
        <select name="category" class="admin-select" style="width: 180px">
          <option value="">All categories</option>
          @foreach(\App\Models\Faq::CATEGORIES as $key => $label)
            <option value="{{ $key }}" @selected(request('category') === $key)>{{ $label }}</option>
          @endforeach
        </select>
        <select name="status" class="admin-select" style="width: 160px">
          <option value="">All statuses</option>
          @foreach(\App\Models\Faq::STATUSES as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
      </form>
      <a href="{{ route('admin.faqs.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New FAQ</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Question</th>
          <th>Category</th>
          <th>Status</th>
          <th>Order</th>
          <th>Published</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($faqs as $faq)
          <tr>
            <td>
              <div class="fw-bold">{{ $faq->question }}</div>
              <div class="small text-muted">{{ Str::limit(strip_tags($faq->answer), 110) }}</div>
            </td>
            <td>{{ $faq->categoryLabel() }}</td>
            <td><span class="admin-badge {{ $faq->status }}">{{ ucfirst($faq->status) }}</span></td>
            <td>{{ $faq->sort_order }}</td>
            <td>{{ $faq->published_at?->format('M j, Y') ?? 'Not published' }}</td>
            <td class="text-end"><a href="{{ route('admin.faqs.edit', $faq) }}" class="admin-btn-secondary">Edit</a></td>
          </tr>
        @empty
          @include('admin.partials.empty-table', [
            'colspan' => 6,
            'icon' => 'bi-question-circle',
            'title' => request()->query() ? 'No FAQs match your filters' : 'No FAQs yet',
            'message' => request()->query() ? 'Try another question, category, or status.' : 'Create FAQs to populate the public resources page.',
            'actionLabel' => 'New FAQ',
            'actionUrl' => route('admin.faqs.create'),
            'resetUrl' => route('admin.faqs.index'),
          ])
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">{{ $faqs->links() }}</div>
  </section>
@endsection
