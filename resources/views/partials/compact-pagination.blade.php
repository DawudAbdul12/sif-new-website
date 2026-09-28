@if($paginator->hasPages())
  @once
    <style>
      .compact-pagination {
        flex-wrap: wrap;
        gap: 6px;
      }

      .compact-pagination .page-link {
        border-radius: 6px;
        font-weight: 700;
        min-width: 38px;
        text-align: center;
      }

      .compact-pagination .page-item:first-child .page-link,
      .compact-pagination .page-item:last-child .page-link {
        min-width: 96px;
      }

      .compact-pagination-icon {
        display: none;
      }

      @media (max-width: 575.98px) {
        .compact-pagination {
          justify-content: center;
        }

        .compact-pagination .page-item:first-child .page-link,
        .compact-pagination .page-item:last-child .page-link {
          min-width: 38px;
        }

        .compact-pagination-text {
          display: none;
        }

        .compact-pagination-icon {
          display: inline;
        }
      }
    </style>
  @endonce

  @php
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();
    $pages = collect([1, $current - 1, $current, $current + 1, $last])
      ->filter(fn ($page) => $page >= 1 && $page <= $last)
      ->unique()
      ->values();
    $previousPage = null;
  @endphp

  <nav role="navigation" aria-label="Pagination Navigation">
    <ul class="pagination compact-pagination">
      <li class="page-item @if($paginator->onFirstPage()) disabled @endif">
        <a class="page-link" href="{{ $paginator->previousPageUrl() ?: '#' }}" rel="prev" aria-label="Previous">
          <span class="compact-pagination-text">Previous</span>
          <span class="compact-pagination-icon">&lsaquo;</span>
        </a>
      </li>

      @foreach($pages as $page)
        @if($previousPage && $page > $previousPage + 1)
          <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
        @endif

        <li class="page-item @if($page === $current) active @endif">
          @if($page === $current)
            <span class="page-link" aria-current="page">{{ $page }}</span>
          @else
            <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
          @endif
        </li>

        @php $previousPage = $page; @endphp
      @endforeach

      <li class="page-item @if(! $paginator->hasMorePages()) disabled @endif">
        <a class="page-link" href="{{ $paginator->nextPageUrl() ?: '#' }}" rel="next" aria-label="Next">
          <span class="compact-pagination-text">Next</span>
          <span class="compact-pagination-icon">&rsaquo;</span>
        </a>
      </li>
    </ul>
  </nav>
@endif
