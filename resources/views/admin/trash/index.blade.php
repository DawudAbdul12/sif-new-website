@extends('admin.layouts.app')

@section('title', 'Trash')
@section('page_title', 'Trash')
@section('page_subtitle', 'Restore soft-deleted records or permanently remove them.')

@push('styles')
  <style>
    .trash-layout {
      display: grid;
      gap: 18px;
      grid-template-columns: 260px minmax(0, 1fr);
    }

    .trash-types {
      display: grid;
      gap: 8px;
    }

    .trash-type-link {
      align-items: center;
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      color: var(--gb-ink);
      display: flex;
      justify-content: space-between;
      padding: 10px 12px;
      text-decoration: none;
    }

    .trash-type-link:hover,
    .trash-type-link.active {
      background: #edf5ef;
      border-color: rgba(23, 71, 45, .2);
      color: var(--gb-green);
    }

    .trash-type-label {
      font-size: .86rem;
      font-weight: 850;
    }

    .trash-type-count {
      background: #fff;
      border: 1px solid var(--gb-line);
      border-radius: 999px;
      color: var(--gb-muted);
      font-size: .72rem;
      font-weight: 900;
      min-width: 30px;
      padding: 4px 8px;
      text-align: center;
    }

    .trash-toolbar {
      align-items: center;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: space-between;
      margin-bottom: 16px;
    }

    .trash-search {
      display: flex;
      flex: 1;
      gap: 10px;
      max-width: 560px;
    }

    .trash-table-wrap {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 .5rem 1.25rem rgba(15, 23, 42, .08);
      overflow: hidden;
      padding: 16px;
    }

    .trash-table {
      table-layout: fixed;
      width: 100%;
    }

    .trash-table th {
      background: #f8faf9;
      color: var(--gb-muted);
      font-size: .76rem;
      font-weight: 900;
      text-transform: uppercase;
    }

    .trash-table td,
    .trash-table th {
      padding: 14px 12px;
      vertical-align: middle;
    }

    .trash-record-title {
      color: var(--gb-green);
      display: block;
      font-weight: 900;
      overflow-wrap: anywhere;
    }

    .trash-muted {
      color: var(--gb-muted);
      display: block;
      font-size: .78rem;
      font-weight: 700;
      margin-top: 3px;
    }

    .trash-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      justify-content: flex-end;
    }

    .trash-inline-form {
      display: inline;
    }

    @media (max-width: 991.98px) {
      .trash-layout {
        grid-template-columns: 1fr;
      }

      .trash-types {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 575.98px) {
      .trash-types,
      .trash-search {
        grid-template-columns: 1fr;
      }

      .trash-search {
        display: grid;
      }

      .trash-table th:nth-child(2),
      .trash-table td:nth-child(2) {
        display: none;
      }
    }
  </style>
@endpush

@section('content')
  <div class="trash-layout">
    <aside class="admin-panel">
      <div class="trash-types">
        @foreach($types as $key => $config)
          <a href="{{ route('admin.trash.index', ['type' => $key]) }}" class="trash-type-link @if($activeType === $key) active @endif">
            <span class="trash-type-label">{{ $config['label'] }}</span>
            <span class="trash-type-count">{{ number_format($counts[$key] ?? 0) }}</span>
          </a>
        @endforeach
      </div>
    </aside>

    <section class="admin-panel">
      <div class="trash-toolbar">
        <form method="GET" class="trash-search">
          <input type="hidden" name="type" value="{{ $activeType }}">
          <input type="search" name="search" value="{{ request('search') }}" class="admin-control" placeholder="Search deleted {{ strtolower($types[$activeType]['label']) }}">
          <button class="admin-btn-secondary" type="submit"><i class="bi bi-search"></i> Search</button>
          @if(request()->filled('search'))
            <a href="{{ route('admin.trash.index', ['type' => $activeType]) }}" class="admin-btn-secondary"><i class="bi bi-x-lg"></i> Reset</a>
          @endif
        </form>
      </div>

      <div class="trash-table-wrap">
        <table class="table align-middle trash-table mb-0">
          <thead>
            <tr>
              <th>Record</th>
              <th>Deleted</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($items as $item)
              <tr>
                <td>
                  <span class="trash-record-title">
                    {{ $item->title ?? $item->name ?? $item->business_name ?? $item->label ?? $item->key ?? $item->email ?? $item->file_name ?? $item->slug ?? class_basename($item).' #'.$item->getKey() }}
                  </span>
                  <span class="trash-muted">{{ class_basename($types[$activeType]['model']) }} #{{ $item->getKey() }}</span>
                </td>
                <td>
                  <span class="trash-muted">{{ $item->deleted_at?->diffForHumans() }}</span>
                  <span class="trash-muted">{{ $item->deleted_at?->format('d M Y, H:i') }}</span>
                </td>
                <td>
                  <div class="trash-actions">
                    <form method="POST" action="{{ route('admin.trash.restore', [$activeType, $item->getKey()]) }}" class="trash-inline-form">
                      @csrf
                      <button class="admin-btn-secondary" type="submit"><i class="bi bi-arrow-counterclockwise"></i> Restore</button>
                    </form>
                    <form method="POST" action="{{ route('admin.trash.destroy', [$activeType, $item->getKey()]) }}" class="trash-inline-form" onsubmit="return confirm('Permanently delete this record? This cannot be undone.')">
                      @csrf
                      @method('DELETE')
                      <button class="admin-danger" type="submit"><i class="bi bi-trash"></i> Delete Forever</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              @include('admin.partials.empty-table', [
                'colspan' => 3,
                'icon' => 'bi-trash3',
                'title' => 'No deleted '.strtolower($types[$activeType]['label']).' found',
                'message' => 'Soft-deleted records for this section will appear here with restore and permanent delete actions.',
              ])
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3">@include('partials.compact-pagination', ['paginator' => $items])</div>
    </section>
  </div>
@endsection
