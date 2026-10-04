@if(method_exists($paginator, 'total') && $paginator->total() > 0)
  <p style="color:var(--ink-soft);font-size:14px;margin:18px 0;">
    Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
  </p>
@endif

@include('partials.compact-pagination', ['paginator' => $paginator])
