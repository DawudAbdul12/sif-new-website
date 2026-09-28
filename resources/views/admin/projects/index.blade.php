@extends('admin.layouts.app')

@section('title', 'Projects')
@section('page_title', 'Projects')
@section('page_subtitle', 'Manage project cards, filters, map markers, and detail pages.')

@section('content')
  <section class="admin-panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
      <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" class="admin-control" style="width: 260px" placeholder="Search projects">
        <select name="project_status" class="admin-select" style="width: 170px">
          <option value="">All project states</option>
          @foreach(\App\Models\Project::PROJECT_STATUSES as $status)
            <option value="{{ $status }}" @selected(request('project_status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <select name="zone_key" class="admin-select" style="width: 210px">
          <option value="">All zones</option>
          @foreach(\App\Models\Project::ZONES as $key => $label)
            <option value="{{ $key }}" @selected(request('zone_key') === $key)>{{ $label }}</option>
          @endforeach
        </select>
        <select name="status" class="admin-select" style="width: 160px">
          <option value="">All statuses</option>
          @foreach(\App\Models\Project::STATUSES as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button class="admin-btn-secondary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
      </form>
      <a href="{{ route('admin.projects.create') }}" class="admin-btn"><i class="bi bi-plus-lg"></i> New Project</a>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Project</th>
          <th>State</th>
          <th>Zone</th>
          <th>Regions</th>
          <th>Published</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($projects as $project)
          <tr>
            <td>
              <div class="fw-bold">{{ $project->name }}</div>
              <div class="small text-muted">{{ Str::limit($project->full_name, 80) }}</div>
              <div class="small text-muted">/{{ $project->slug }}</div>
            </td>
            <td>
              <span class="status-pill {{ $project->project_status }}">{{ ucfirst($project->project_status) }}</span>
              <span class="admin-badge {{ $project->status }} ms-1">{{ ucfirst($project->status) }}</span>
            </td>
            <td>{{ $project->zone_name ?: (\App\Models\Project::ZONES[$project->zone_key] ?? '-') }}</td>
            <td>{{ collect($project->regions)->take(3)->implode(', ') ?: '-' }}</td>
            <td>{{ $project->published_at?->format('M j, Y') ?? 'Not published' }}</td>
            <td class="text-end"><a href="{{ route('admin.projects.edit', $project) }}" class="admin-btn-secondary">Edit</a></td>
          </tr>
        @empty
          @include('admin.partials.empty-table', [
            'colspan' => 6,
            'icon' => 'bi-kanban',
            'title' => request()->query() ? 'No projects match your filters' : 'No projects yet',
            'message' => request()->query() ? 'Try another project name, status, or zone.' : 'Create project records to power the public project cards, filters, maps, and detail pages.',
            'actionLabel' => 'New Project',
            'actionUrl' => route('admin.projects.create'),
            'resetUrl' => route('admin.projects.index'),
          ])
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">{{ $projects->links() }}</div>
  </section>
@endsection
