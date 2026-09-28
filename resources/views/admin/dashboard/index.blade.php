@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'CMS overview and publishing activity.')

@push('styles')
  <style>
    .dashboard-shell { display: grid; gap: 18px; }
    .dashboard-hero {
      background: radial-gradient(circle at 84% 10%, rgba(9, 167, 71, .28), transparent 32%), linear-gradient(135deg, #07111f 0%, #111827 52%, var(--gb-green) 130%);
      border: 1px solid rgba(255, 255, 255, .16);
      border-radius: 22px;
      box-shadow: 0 24px 60px rgba(17, 24, 39, .22);
      color: #fff;
      display: grid;
      gap: 28px;
      grid-template-columns: minmax(0, 1.1fr) minmax(300px, .9fr);
      min-height: 248px;
      overflow: hidden;
      padding: 30px;
      position: relative;
    }
    .dashboard-hero::after { background: rgba(255, 255, 255, .09); border-radius: 50%; bottom: -150px; content: ""; height: 360px; position: absolute; right: -120px; width: 360px; }
    .dashboard-hero > * { position: relative; z-index: 1; }
    .dashboard-eyebrow { align-items: center; background: rgba(255, 255, 255, .08); border: 1px solid rgba(255, 255, 255, .14); border-radius: 999px; color: rgba(255, 255, 255, .78); display: inline-flex; font-size: 11px; font-weight: 800; gap: 8px; letter-spacing: .08em; padding: 7px 10px; text-transform: uppercase; width: fit-content; }
    .dashboard-eyebrow::before { background: #22c55e; border-radius: 50%; box-shadow: 0 0 0 5px rgba(34, 197, 94, .14); content: ""; height: 7px; width: 7px; }
    .dashboard-hero-title { font-size: 46px; font-weight: 800; letter-spacing: 0; line-height: 1; margin-top: 18px; max-width: 620px; }
    .dashboard-hero-copy { color: rgba(255, 255, 255, .72); font-size: 15px; line-height: 1.65; margin-top: 14px; max-width: 560px; }
    .dashboard-hero-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 24px; }
    .dashboard-primary, .dashboard-secondary { align-items: center; border-radius: 999px; display: inline-flex; font-size: 13px; font-weight: 800; gap: 8px; justify-content: center; min-height: 42px; padding: 0 16px; }
    .dashboard-primary { background: #fff; box-shadow: 0 16px 30px rgba(0, 0, 0, .18); color: #111827; }
    .dashboard-secondary { background: rgba(255, 255, 255, .08); border: 1px solid rgba(255, 255, 255, .18); color: #fff; }
    .dashboard-hero-panel { align-self: stretch; background: rgba(255, 255, 255, .09); border: 1px solid rgba(255, 255, 255, .14); border-radius: 18px; display: grid; gap: 12px; padding: 16px; }
    .dashboard-hero-metric { align-items: center; background: rgba(255, 255, 255, .1); border-radius: 14px; display: flex; gap: 16px; justify-content: space-between; padding: 14px; }
    .dashboard-hero-label { color: rgba(255, 255, 255, .68); font-size: 11px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
    .dashboard-hero-value { font-size: 28px; font-weight: 800; letter-spacing: 0; margin-top: 5px; }
    .dashboard-hero-note { color: rgba(255, 255, 255, .62); font-size: 12px; font-weight: 700; text-align: right; }
    .dashboard-kpis { display: grid; gap: 12px; grid-template-columns: repeat(4, minmax(0, 1fr)); }
    .dashboard-kpi { background: rgba(255, 255, 255, .86); border: 1px solid rgba(226, 232, 240, .95); border-radius: 16px; box-shadow: 0 14px 36px rgba(17, 24, 39, .06); min-width: 0; padding: 16px; }
    .dashboard-kpi-top { align-items: center; display: flex; gap: 10px; justify-content: space-between; }
    .dashboard-kpi-icon { background: var(--admin-blue-light); border-radius: 11px; color: var(--gb-green); display: grid; height: 34px; place-items: center; width: 34px; }
    .dashboard-kpi-label { color: var(--admin-gray-500); font-size: 11px; font-weight: 900; letter-spacing: .07em; margin-top: 18px; text-transform: uppercase; }
    .dashboard-kpi-value { color: var(--admin-gray-900); font-size: 25px; font-weight: 850; letter-spacing: 0; margin-top: 5px; }
    .dashboard-grid { align-items: start; display: grid; gap: 18px; grid-template-columns: minmax(0, 1.38fr) minmax(320px, .62fr); }
    .dashboard-stack { display: grid; gap: 18px; }
    .premium-card { background: #fff; border: 1px solid rgba(226, 232, 240, .95); border-radius: 18px; box-shadow: 0 16px 42px rgba(17, 24, 39, .07); overflow: hidden; }
    .premium-card-header { align-items: flex-start; border-bottom: 1px solid var(--admin-gray-100); display: flex; gap: 14px; justify-content: space-between; padding: 18px 20px; }
    .premium-title { color: var(--admin-gray-900); font-size: 16px; font-weight: 850; letter-spacing: 0; }
    .premium-subtitle { color: var(--admin-gray-500); font-size: 13px; line-height: 1.45; margin-top: 4px; }
    .premium-link { color: var(--gb-green); font-size: 12px; font-weight: 850; white-space: nowrap; }
    .inventory-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); }
    .inventory-tile { border-bottom: 1px solid var(--admin-gray-100); border-right: 1px solid var(--admin-gray-100); color: inherit; min-height: 102px; padding: 14px; transition: background .16s ease, box-shadow .16s ease, transform .16s ease; }
    .inventory-tile:nth-child(5n) { border-right: 0; }
    .inventory-tile:hover { background: #f8fbff; box-shadow: inset 0 0 0 1px rgba(6, 16, 90, .08); transform: translateY(-1px); }
    .inventory-icon { align-items: center; background: var(--admin-blue-light); border-radius: 8px; color: var(--gb-green); display: inline-flex; height: 34px; justify-content: center; margin-bottom: 10px; width: 34px; }
    .inventory-label { color: var(--admin-gray-500); display: block; font-size: 11px; font-weight: 850; letter-spacing: .04em; text-transform: uppercase; }
    .inventory-value { color: var(--admin-gray-900); display: block; font-size: 20px; font-weight: 900; line-height: 1; margin-top: 5px; }
    .premium-table-wrap { overflow-x: auto; }
    .premium-table { border-collapse: collapse; min-width: 760px; width: 100%; }
    .premium-table th { border-bottom: 1px solid var(--admin-gray-100); color: var(--admin-gray-400); font-size: 10px; font-weight: 900; letter-spacing: .08em; padding: 12px 20px; text-align: left; text-transform: uppercase; white-space: nowrap; }
    .premium-table td { border-bottom: 1px solid var(--admin-gray-100); color: var(--admin-gray-700); font-size: 13px; padding: 15px 20px; vertical-align: middle; }
    .premium-table tr:last-child td { border-bottom: 0; }
    .premium-table strong { color: var(--admin-gray-900); display: block; font-size: 13px; }
    .premium-table small { color: var(--admin-gray-400); display: block; font-size: 12px; margin-top: 3px; }
    .activity-list { display: grid; gap: 10px; padding: 16px; }
    .activity-item { align-items: flex-start; background: #fbfdff; border: 1px solid var(--admin-gray-100); border-radius: 13px; color: inherit; display: flex; gap: 12px; padding: 12px; }
    .activity-icon { background: var(--admin-blue-light); border-radius: 11px; color: var(--gb-green); display: grid; flex: 0 0 36px; height: 36px; place-items: center; width: 36px; }
    .activity-title { color: var(--admin-gray-900); display: block; font-size: 13px; font-weight: 850; line-height: 1.35; }
    .activity-meta { color: var(--admin-gray-400); display: block; font-size: 12px; line-height: 1.45; margin-top: 3px; }
    .action-grid { display: grid; gap: 12px; padding: 16px; }
    .action-card { background: linear-gradient(180deg, #fff, #fbfdff); border: 1px solid var(--admin-gray-200); border-radius: 14px; color: inherit; display: flex; gap: 12px; padding: 14px; transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease; }
    .action-card:hover { border-color: rgba(6, 16, 90, .22); box-shadow: 0 14px 28px rgba(6, 16, 90, .08); transform: translateY(-1px); }
    .action-icon { background: var(--admin-gray-900); border-radius: 12px; color: #fff; display: grid; flex: 0 0 38px; height: 38px; place-items: center; width: 38px; }
    .action-title { color: var(--admin-gray-900); display: block; font-size: 13px; font-weight: 850; }
    .action-copy { color: var(--admin-gray-500); display: block; font-size: 12px; line-height: 1.45; margin-top: 4px; }
    @media (max-width: 1180px) {
      .dashboard-hero, .dashboard-grid { grid-template-columns: 1fr; }
      .dashboard-kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .inventory-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
      .inventory-tile:nth-child(5n) { border-right: 1px solid var(--admin-gray-100); }
      .inventory-tile:nth-child(3n) { border-right: 0; }
    }
    @media (max-width: 700px) {
      .dashboard-hero { border-radius: 18px; padding: 22px; }
      .dashboard-hero-title { font-size: 32px; line-height: 1.08; }
      .dashboard-hero-actions { align-items: stretch; flex-direction: column; }
      .dashboard-hero-metric { align-items: flex-start; flex-direction: column; }
      .dashboard-hero-note { text-align: left; }
      .dashboard-kpis, .inventory-grid { grid-template-columns: 1fr; }
      .inventory-tile, .inventory-tile:nth-child(3n), .inventory-tile:nth-child(5n) { border-right: 0; }
      .premium-card-header { flex-direction: column; }
    }
  </style>
@endpush

@section('content')
  <div class="dashboard-shell">
    <section class="dashboard-hero">
      <div>
        <div class="dashboard-eyebrow">Publishing Control Room</div>
        <div class="dashboard-hero-title">Website operations, without the clutter.</div>
        <div class="dashboard-hero-copy">A focused command center for pages, news, media, resource documents, and publishing activity.</div>
        <div class="dashboard-hero-actions">
          @can('posts.create')
            <a href="{{ route('admin.posts.create') }}" class="dashboard-primary"><i class="bi bi-plus-lg"></i> New Post</a>
          @endcan
          @can('media.create')
            <a href="{{ route('admin.media.create') }}" class="dashboard-secondary"><i class="bi bi-upload"></i> Upload Media</a>
          @endcan
          @can('activity.view')
            <a href="{{ route('admin.activity-logs.index') }}" class="dashboard-secondary"><i class="bi bi-activity"></i> Activity Log</a>
          @endcan
        </div>
      </div>

      <div class="dashboard-hero-panel">
        <div class="dashboard-hero-metric">
          <div><div class="dashboard-hero-label">Published posts</div><div class="dashboard-hero-value">{{ number_format($publishedPostCount) }}</div></div>
          <div class="dashboard-hero-note">{{ number_format($draftPostCount) }} drafts waiting</div>
        </div>
        <div class="dashboard-hero-metric">
          <div><div class="dashboard-hero-label">Live pages</div><div class="dashboard-hero-value">{{ number_format($publishedPageCount) }}</div></div>
          <div class="dashboard-hero-note">{{ number_format($pageCount) }} pages total</div>
        </div>
        <div class="dashboard-hero-metric">
          <div><div class="dashboard-hero-label">Trash</div><div class="dashboard-hero-value">{{ number_format($trashCount) }}</div></div>
          <div class="dashboard-hero-note">Soft-deleted records</div>
        </div>
      </div>
    </section>

    <section class="dashboard-kpis">
      @foreach([
        ['Pages', $pageCount, 'bi-file-earmark-text', 'pages.view'],
        ['Posts', $postCount, 'bi-newspaper', 'posts.view'],
        ['Projects', $projectCount, 'bi-kanban', 'projects.view'],
        ['FAQs', $faqCount, 'bi-question-circle', 'faqs.view'],
        ['Impact', $impactMetricCount, 'bi-bar-chart-line', 'impact.view'],
        ['Media', $mediaCount, 'bi-folder2-open', 'media.view'],
        ['Activity', $activityCount, 'bi-activity', 'activity.view'],
      ] as [$label, $count, $icon, $permission])
        @cannot($permission)
          @continue
        @endcannot
        <div class="dashboard-kpi">
          <div class="dashboard-kpi-top">
            <span class="dashboard-kpi-icon"><i class="bi {{ $icon }}"></i></span>
            <span class="health-pill info">Live</span>
          </div>
          <div class="dashboard-kpi-label">{{ $label }}</div>
          <div class="dashboard-kpi-value">{{ number_format($count) }}</div>
        </div>
      @endforeach
    </section>

    <section class="premium-card">
      <div class="premium-card-header">
        <div>
          <div class="premium-title">Content Inventory</div>
          <div class="premium-subtitle">Active records grouped by the main CMS work areas.</div>
        </div>
        @can('trash.view')
          <a href="{{ route('admin.trash.index') }}" class="premium-link">Open trash</a>
        @endcan
      </div>

      <div class="inventory-grid">
        @foreach([
          ['Pages', $pageCount, 'bi-file-earmark-text', route('admin.pages.index'), 'pages.view'],
          ['Posts', $postCount, 'bi-newspaper', route('admin.posts.index'), 'posts.view'],
          ['Projects', $projectCount, 'bi-kanban', route('admin.projects.index'), 'projects.view'],
          ['FAQs', $faqCount, 'bi-question-circle', route('admin.faqs.index'), 'faqs.view'],
          ['Impact', $impactMetricCount, 'bi-bar-chart-line', route('admin.impact-metrics.index'), 'impact.view'],
          ['Categories', $categoryCount, 'bi-tags', route('admin.categories.index'), 'categories.view'],
          ['Press', $pressReleaseCount, 'bi-megaphone', route('admin.press-releases.index'), 'press.view'],
          ['Notices', $noticeCount, 'bi-bell', route('admin.notices.index'), 'notices.view'],
          ['Videos', $videoCount, 'bi-play-btn', route('admin.videos.index'), 'videos.view'],
          ['Gallery', $galleryCount, 'bi-images', route('admin.gallery.index'), 'gallery.view'],
          ['Graphics', $graphicCount, 'bi-easel', route('admin.graphics.index'), 'graphics.view'],
          ['Media', $mediaCount, 'bi-folder2-open', route('admin.media.index'), 'media.view'],
          ['Docs', $documentCount, 'bi-file-earmark-lock', route('admin.documents.index', 'publications'), 'documents.view'],
          ['People', $peopleCount, 'bi-person-badge', route('admin.people.index', 'board'), 'people.view'],
          ['Admins', $adminCount, 'bi-people', route('admin.users.index'), 'users.view'],
          ['Activity', $activityCount, 'bi-activity', route('admin.activity-logs.index'), 'activity.view'],
        ] as [$label, $count, $icon, $url, $permission])
          @cannot($permission)
            @continue
          @endcannot
          <a href="{{ $url }}" class="inventory-tile">
            <span class="inventory-icon"><i class="bi {{ $icon }}"></i></span>
            <span class="inventory-label">{{ $label }}</span>
            <span class="inventory-value">{{ number_format($count) }}</span>
          </a>
        @endforeach
      </div>
    </section>

    <div class="dashboard-grid">
      <div class="dashboard-stack">
        <section class="premium-card">
          <div class="premium-card-header">
            <div>
              <div class="premium-title">Recent Posts</div>
              <div class="premium-subtitle">Latest editorial records and publishing states.</div>
            </div>
            @can('posts.create')
              <a href="{{ route('admin.posts.create') }}" class="premium-link">Create post</a>
            @endcan
          </div>

          <div class="premium-table-wrap">
            <table class="premium-table">
              <thead><tr><th>Title</th><th>Type</th><th>Category</th><th>Status</th><th>Updated</th></tr></thead>
              <tbody>
                @forelse($recentPosts as $post)
                  <tr>
                    <td>
                      @can('posts.update')
                        <a href="{{ route('admin.posts.edit', $post) }}"><strong>{{ $post->title }}</strong></a>
                      @else
                        <strong>{{ $post->title }}</strong>
                      @endcan
                      <small>{{ $post->slug }}</small>
                    </td>
                    <td>{{ ucfirst($post->type) }}</td>
                    <td>{{ $post->categoryRelation?->name ?? 'Uncategorized' }}</td>
                    <td><span class="status-pill {{ $post->status }}">{{ ucfirst($post->status) }}</span></td>
                    <td>{{ $post->updated_at->diffForHumans() }}</td>
                  </tr>
                @empty
                  @include('admin.partials.empty-table', [
                    'colspan' => 5,
                    'icon' => 'bi-newspaper',
                    'title' => 'No recent posts yet',
                    'message' => 'New and recently updated posts will appear here once publishing activity starts.',
                    'actionLabel' => 'New Post',
                    'actionUrl' => route('admin.posts.create'),
                  ])
                @endforelse
              </tbody>
            </table>
          </div>
        </section>
      </div>

      <div class="dashboard-stack">
        <section class="premium-card">
          <div class="premium-card-header">
            <div>
              <div class="premium-title">Recent Activity</div>
              <div class="premium-subtitle">Latest admin changes across the CMS.</div>
            </div>
            @can('activity.view')
              <a href="{{ route('admin.activity-logs.index') }}" class="premium-link">View all</a>
            @endcan
          </div>

          <div class="activity-list">
            @forelse($recentActivities as $activity)
              @can('activity.view')
                <a href="{{ route('admin.activity-logs.show', $activity) }}" class="activity-item">
                  <span class="activity-icon"><i class="bi bi-clock-history"></i></span>
                  <span><span class="activity-title">{{ $activity->actionLabel() }} {{ $activity->subjectName() }}</span><span class="activity-meta">{{ $activity->causer?->name ?? $activity->causer_name ?? 'System' }} · {{ $activity->created_at?->diffForHumans() }}</span></span>
                </a>
              @else
                <div class="activity-item">
                  <span class="activity-icon"><i class="bi bi-clock-history"></i></span>
                  <span><span class="activity-title">{{ $activity->actionLabel() }} {{ $activity->subjectName() }}</span><span class="activity-meta">{{ $activity->causer?->name ?? $activity->causer_name ?? 'System' }} · {{ $activity->created_at?->diffForHumans() }}</span></span>
                </div>
              @endcan
            @empty
              <div class="activity-item"><span class="activity-icon"><i class="bi bi-clock-history"></i></span><span class="activity-meta">No activity has been recorded yet.</span></div>
            @endforelse
          </div>
        </section>

        <section class="premium-card">
          <div class="premium-card-header">
            <div>
              <div class="premium-title">Quick Actions</div>
              <div class="premium-subtitle">The main publishing paths for day-to-day work.</div>
            </div>
          </div>
          <div class="action-grid">
            @can('pages.view')
              <a class="action-card" href="{{ route('admin.pages.index') }}"><span class="action-icon"><i class="bi bi-file-earmark-text"></i></span><span><span class="action-title">Manage pages</span><span class="action-copy">Update static website pages and SEO details.</span></span></a>
            @endcan
            @can('media.view')
              <a class="action-card" href="{{ route('admin.media.index') }}"><span class="action-icon"><i class="bi bi-images"></i></span><span><span class="action-title">Open media library</span><span class="action-copy">Review uploaded images, documents, captions, and alt text.</span></span></a>
            @endcan
            @can('posts.view')
              <a class="action-card" href="{{ route('admin.posts.index') }}"><span class="action-icon"><i class="bi bi-newspaper"></i></span><span><span class="action-title">Review posts</span><span class="action-copy">Open news and articles for editing, review, and publishing.</span></span></a>
            @endcan
          </div>
        </section>
      </div>
    </div>
  </div>
@endsection
