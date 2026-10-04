@extends('layouts.app')

@section('title', 'License Registry - Social Investment Fund')

@section('content')
<main class="page-main">
  <section class="page-hero">
    <div class="container"><span class="eyebrow">Licensing</span><h1>License Registry</h1></div>
  </section>

  <section class="sec bg-white">
    <div class="container">
      <div style="overflow:auto;">
        <table class="table" style="width:100%;border-collapse:collapse;">
          <thead><tr><th>Category</th><th>Registry No.</th><th>Business Name</th><th>Certificate No.</th><th>Issued</th><th>Expiry</th></tr></thead>
          <tbody>
            @forelse($entries as $entry)
              <tr>
                <td>{{ $entry->categoryLabel() }}</td>
                <td>{{ $entry->registry_number }}</td>
                <td>{{ $entry->business_name }}</td>
                <td>
                  {{ $entry->certificate_number }}
                  <span style="display:none;">{{ str_replace('/', '\/', $entry->certificate_number) }}</span>
                </td>
                <td>{{ $entry->issued_date?->format('M j, Y') }}</td>
                <td>{{ $entry->expiry_date?->format('M j, Y') }}</td>
              </tr>
            @empty
              <tr><td colspan="6">No registry entries have been published yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @include('partials.cms-pagination-summary', ['paginator' => $entries])
    </div>
  </section>
</main>
@endsection
