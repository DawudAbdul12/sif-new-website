<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin') | SIF CMS</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
    rel="stylesheet">
  <link
    href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700;9..40,800&display=swap"
    rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    :root {
      --gb-green: #06105A;
      --gb-green-dark: #081B78;
      --gb-gold: #F4C400;
      --gb-accent: #09A747;
      --gb-ink: #111827;
      --gb-muted: #6B7280;
      --gb-soft: #F4F7FF;
      --gb-line: #E5E7EB;
      --gb-bg: #F8FAFC;
      --gb-red: #EF4444;
      --gb-red-soft: #FEF2F2;
      --gb-white: #FFFFFF;
      --admin-sidebar-w: 224px;
      --admin-radius: 10px;
      --admin-blue-light: #EFF4FF;
      --admin-gray-50: #F9FAFB;
      --admin-gray-100: #F3F4F6;
      --admin-gray-200: #E5E7EB;
      --admin-gray-300: #D1D5DB;
      --admin-gray-400: #9CA3AF;
      --admin-gray-500: #6B7280;
      --admin-gray-700: #374151;
      --admin-gray-900: #111827;
    }

    * {
      box-sizing: border-box;
    }

    html {
      min-height: 100%;
    }

    body {
      margin: 0;
      min-height: 100vh;
      background: var(--gb-bg);
      color: var(--gb-ink);
      font-family: "DM Sans", Montserrat, sans-serif;
      font-size: 14px;
      letter-spacing: 0;
      text-rendering: optimizeLegibility;
      -webkit-font-smoothing: antialiased;
    }

    button,
    input,
    select,
    textarea {
      font: inherit;
    }

    a,
    button {
      text-decoration: none;
    }

    img {
      max-width: 100%;
    }

    .admin-shell {
      display: flex;
      min-height: 100vh;
      width: 100%;
    }

    .admin-sidebar {
      background: var(--gb-white);
      border-right: 1px solid var(--gb-line);
      color: var(--gb-ink);
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      height: 100dvh;
      overflow: hidden;
      position: sticky;
      scrollbar-width: thin;
      scrollbar-color: rgba(6, 16, 90, .28) transparent;
      top: 0;
      width: var(--admin-sidebar-w);
      z-index: 40;
    }

    .admin-sidebar::-webkit-scrollbar {
      width: 6px;
    }

    .admin-sidebar::-webkit-scrollbar-thumb {
      background: rgba(6, 16, 90, .2);
      border-radius: 999px;
    }

    .admin-sidebar-brand {
      align-items: center;
      border-bottom: 1px solid #F3F4F6;
      display: flex;
      min-height: 72px;
      padding: 16px 20px;
    }

    .admin-sidebar-logo {
      display: block;
      height: auto;
      max-height: 48px;
      max-width: 116px;
      width: auto;
    }

    .admin-nav {
      flex: 1;
      overflow-y: auto;
      padding: 12px;
    }

    .admin-nav-group {
      margin-bottom: 14px;
    }

    .admin-nav-group:last-child {
      margin-bottom: 0;
    }

    .admin-nav-section {
      color: #9CA3AF;
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .08em;
      margin: 14px 12px 7px;
      text-transform: uppercase;
    }

    .admin-nav-list {
      display: grid;
      gap: 2px;
    }

    .admin-nav-link {
      align-items: center;
      background: transparent;
      border-radius: 8px;
      color: #374151;
      display: flex;
      font-size: 14px;
      font-weight: 500;
      gap: 10px;
      min-height: 38px;
      padding: 9px 12px;
      text-align: left;
      transition: background .15s ease, color .15s ease, box-shadow .15s ease;
      width: 100%;
    }

    .admin-nav-link i {
      align-items: center;
      display: inline-flex;
      flex: 0 0 16px;
      font-size: .96rem;
      justify-content: center;
      width: 16px;
    }

    .admin-nav-link span {
      min-width: 0;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .admin-nav-link:hover {
      background: #F3F4F6;
      color: var(--admin-gray-900);
    }

    .admin-nav-link.active {
      background: var(--gb-green);
      color: #fff;
      box-shadow: 0 10px 22px rgba(6, 16, 90, .18);
    }

    .admin-nav-link.admin-nav-external {
      border: 1px solid var(--gb-line);
      margin-top: 4px;
    }

    .admin-nav-link.admin-nav-external.active,
    .admin-nav-link.admin-nav-external:hover {
      border-color: rgba(6, 16, 90, .18);
    }

    .admin-sidebar-footer {
      border-top: 1px solid var(--admin-gray-100);
      display: grid;
      gap: 8px;
      padding: 12px;
    }

    .admin-user-row {
      align-items: center;
      border-radius: 8px;
      color: inherit;
      display: flex;
      gap: 10px;
      min-width: 0;
      padding: 8px 10px;
    }

    .admin-user-row:hover {
      background: var(--admin-gray-100);
    }

    .admin-user-avatar {
      align-items: center;
      background: var(--gb-green);
      border-radius: 50%;
      color: #fff;
      display: inline-flex;
      flex: 0 0 28px;
      font-size: 11px;
      font-weight: 800;
      height: 28px;
      justify-content: center;
      width: 28px;
    }

    .admin-user-name {
      color: var(--admin-gray-900);
      display: block;
      font-size: 13px;
      font-weight: 600;
      line-height: 1.2;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .admin-user-role {
      color: var(--admin-gray-400);
      display: block;
      font-size: 11px;
      font-weight: 700;
      line-height: 1.2;
      margin-top: 2px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .admin-main {
      display: flex;
      flex: 1;
      flex-direction: column;
      min-height: 100vh;
      min-width: 0;
      overflow: hidden;
    }

    .admin-main.is-loading {
      cursor: progress;
    }

    .admin-main.is-loading .admin-content {
      opacity: .55;
      pointer-events: none;
      transition: opacity .16s ease;
    }

    .admin-topbar {
      align-items: flex-start;
      background: var(--gb-white);
      border-bottom: 1px solid var(--gb-line);
      display: flex;
      gap: 16px;
      justify-content: space-between;
      padding: 16px 28px;
    }

    .admin-topbar-actions {
      align-items: center;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: flex-end;
    }

    .admin-content {
      flex: 1;
      min-height: 0;
      overflow-y: auto;
      padding: 24px 28px;
      -webkit-overflow-scrolling: touch;
    }

    .admin-page-title {
      color: var(--gb-ink);
      font-size: 20px;
      font-weight: 700;
      line-height: 1.2;
      margin: 0;
    }

    .admin-subtitle {
      color: var(--gb-muted);
      font-size: 13px;
      line-height: 1.45;
      margin: 2px 0 0;
      overflow-wrap: anywhere;
    }

    .admin-panel,
    .admin-card {
      background: var(--gb-white);
      border: 1px solid var(--gb-line);
      border-radius: var(--admin-radius);
      box-shadow: 0 10px 28px rgba(17, 24, 39, .07);
    }

    .admin-panel {
      margin-bottom: 20px;
      overflow-x: auto;
      padding: 20px;
    }

    .admin-card {
      margin-bottom: 20px;
      padding: 20px;
    }

    .admin-card-header {
      align-items: flex-start;
      display: flex;
      gap: 16px;
      justify-content: space-between;
      margin-bottom: 16px;
      position: relative;
    }

    .admin-card-header > div:first-child {
      min-width: 0;
    }

    .admin-card-title {
      color: var(--admin-gray-900);
      font-size: 17px;
      font-weight: 700;
      line-height: 1.25;
      margin: 0;
    }

    .admin-card-sub {
      color: var(--admin-gray-500);
      font-size: 13px;
      line-height: 1.45;
      margin-top: 3px;
      overflow-wrap: anywhere;
    }

    .admin-stat-label {
      color: var(--gb-muted);
      font-size: 12px;
      font-weight: 700;
      letter-spacing: .04em;
      margin-bottom: 8px;
      text-transform: uppercase;
    }

    .admin-stat-value {
      color: var(--gb-ink);
      font-size: 22px;
      font-weight: 700;
      margin: 0;
    }

    .admin-btn,
    .admin-btn-secondary,
    .admin-danger {
      display: inline-flex;
      align-items: center;
      border: 1px solid transparent;
      border-radius: 8px;
      gap: 7px;
      justify-content: center;
      min-height: 38px;
      padding: 8px 14px;
      font-size: 13.5px;
      font-weight: 600;
      line-height: 1.2;
      transition: background .15s ease, border-color .15s ease, color .15s ease, opacity .15s ease, transform .15s ease;
      white-space: nowrap;
    }

    .admin-btn {
      background: var(--gb-green);
      color: #fff;
    }

    .admin-btn:hover {
      background: var(--gb-green-dark);
      color: #fff;
      opacity: .9;
      transform: translateY(-1px);
    }

    .admin-btn-secondary {
      background: #fff;
      border-color: var(--gb-line);
      color: var(--gb-ink);
    }

    .admin-btn-secondary:hover {
      background: #F9FAFB;
      border-color: rgba(6, 16, 90, .2);
      color: var(--gb-green);
    }

    .admin-danger {
      background: #fff;
      border-color: #FECACA;
      color: #b42318;
    }

    .admin-danger:hover {
      background: var(--gb-red-soft);
      color: #B42318;
    }

    .admin-btn-secondary.admin-sidebar-logout {
      background: transparent;
      border-color: transparent;
      color: var(--admin-gray-700);
      justify-content: flex-start;
      width: 100%;
    }

    .admin-btn-secondary.admin-sidebar-logout:hover {
      background: var(--admin-gray-100);
      border-color: transparent;
      color: var(--gb-green);
      transform: none;
    }

    .admin-control,
    .admin-select,
    .admin-textarea {
      background: #fff;
      border: 1px solid var(--admin-gray-200);
      border-radius: 8px;
      color: var(--gb-ink);
      min-height: 40px;
      outline: none;
      padding: 9px 12px;
      transition: border-color .15s ease, box-shadow .15s ease;
      width: 100%;
    }

    .admin-select {
      appearance: none;
      background-image:
        linear-gradient(45deg, transparent 50%, var(--admin-gray-400) 50%),
        linear-gradient(135deg, var(--admin-gray-400) 50%, transparent 50%);
      background-position:
        calc(100% - 17px) 17px,
        calc(100% - 12px) 17px;
      background-size: 5px 5px, 5px 5px;
      background-repeat: no-repeat;
      padding-right: 34px;
    }

    .admin-textarea {
      min-height: 180px;
      line-height: 1.55;
      resize: vertical;
    }

    .admin-control:focus,
    .admin-select:focus,
    .admin-textarea:focus {
      border-color: rgba(6, 16, 90, .55);
      box-shadow: 0 0 0 4px rgba(6, 16, 90, .09);
    }

    .admin-label {
      color: var(--admin-gray-500);
      display: block;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: .05em;
      margin-bottom: 6px;
      text-transform: uppercase;
    }

    .admin-table-wrap {
      overflow-x: auto;
      width: 100%;
      -webkit-overflow-scrolling: touch;
    }

    .admin-table {
      border-collapse: collapse;
      min-width: 760px;
      width: 100%;
    }

    .admin-table th {
      border-bottom: 1px solid var(--gb-line);
      color: var(--admin-gray-400);
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .05em;
      padding: 10px 0;
      text-align: left;
      text-transform: uppercase;
    }

    .admin-table td {
      border-bottom: 1px solid var(--admin-gray-100);
      color: var(--admin-gray-700);
      font-size: 13px;
      padding: 13px 0;
      vertical-align: top;
    }

    .admin-table th:not(:last-child),
    .admin-table td:not(:last-child) {
      padding-right: 28px;
    }

    .admin-table tr:last-child td {
      border-bottom: 0;
    }

    .admin-table tbody tr:hover td {
      background: var(--admin-gray-50);
    }

    .table,
    .admin-table {
      margin-bottom: 0;
    }

    .table-toolbar {
      align-items: center;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: space-between;
      margin: -2px 0 14px;
    }

    .table-search {
      flex: 1;
      min-width: min(260px, 100%);
      position: relative;
    }

    .table-search i,
    .table-search .bi,
    .table-search svg {
      color: var(--admin-gray-400);
      left: 10px;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 1;
    }

    .table-search .admin-control,
    .table-search input {
      padding-left: 34px;
    }

    .table-filter-row {
      align-items: center;
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .admin-table .fit {
      white-space: nowrap;
      width: 1%;
    }

    .muted-cell,
    .admin-table .muted-cell {
      color: var(--admin-gray-400);
      font-size: 12px;
    }

    .admin-badge {
      align-items: center;
      background: #F3F4F6;
      border-radius: 999px;
      color: #374151;
      display: inline-flex;
      font-size: 12px;
      font-weight: 700;
      min-height: 24px;
      padding: 4px 9px;
      text-transform: capitalize;
    }

    .status-pill,
    .health-pill {
      align-items: center;
      background: var(--admin-gray-100);
      border-radius: 999px;
      color: var(--admin-gray-700);
      display: inline-flex;
      font-size: 12px;
      font-weight: 700;
      min-height: 24px;
      padding: 4px 9px;
      text-transform: capitalize;
      white-space: nowrap;
    }

    .health-pill {
      gap: 6px;
      font-size: 11px;
      font-weight: 900;
    }

    .health-pill::before {
      background: currentColor;
      border-radius: 50%;
      content: "";
      height: 6px;
      width: 6px;
    }

    .status-pill.active,
    .status-pill.approved,
    .status-pill.published,
    .status-pill.ongoing,
    .health-pill.ready {
      background: #ECFDF3;
      color: #067647;
    }

    .status-pill.new {
      background: var(--admin-blue-light);
      color: var(--gb-green);
    }

    .status-pill.pending,
    .status-pill.draft,
    .status-pill.review,
    .health-pill.warning {
      background: #FFFAEB;
      color: #B54708;
    }

    .status-pill.archived,
    .status-pill.inactive,
    .status-pill.deleted,
    .status-pill.suspended,
    .status-pill.completed,
    .health-pill.danger {
      background: var(--gb-red-soft);
      color: var(--gb-red);
    }

    .health-pill.info {
      background: var(--admin-blue-light);
      color: var(--gb-green);
    }

    .table-actions {
      align-items: center;
      display: flex;
      gap: 8px;
      justify-content: flex-end;
    }

    .table-actions form {
      margin: 0;
    }

    .icon-btn {
      align-items: center;
      background: transparent;
      border: 0;
      border-radius: 6px;
      color: var(--admin-gray-400);
      display: inline-flex;
      height: 28px;
      justify-content: center;
      padding: 0;
      width: 28px;
    }

    .icon-btn:hover {
      background: var(--admin-gray-100);
      color: var(--admin-gray-700);
    }

    .icon-btn.del:hover {
      background: var(--gb-red-soft);
      color: var(--gb-red);
    }

    .admin-empty-cell {
      padding: 0 !important;
    }

    .admin-empty-state {
      align-items: center;
      background:
        radial-gradient(circle at 50% 0%, rgba(6, 16, 90, .05), transparent 34%),
        linear-gradient(180deg, #fff, #fbfdff);
      display: grid;
      justify-items: center;
      min-height: 250px;
      padding: 34px 18px;
      text-align: center;
    }

    .admin-empty-visual {
      align-items: center;
      background: var(--admin-blue-light);
      border: 1px solid rgba(6, 16, 90, .08);
      border-radius: 16px;
      box-shadow: 0 18px 34px rgba(6, 16, 90, .08);
      color: var(--gb-green);
      display: inline-flex;
      font-size: 1.45rem;
      height: 54px;
      justify-content: center;
      margin-bottom: 14px;
      width: 54px;
    }

    .admin-empty-title {
      color: var(--admin-gray-900);
      font-size: 16px;
      font-weight: 850;
      line-height: 1.25;
      margin: 0;
    }

    .admin-empty-copy {
      color: var(--admin-gray-500);
      font-size: 13px;
      line-height: 1.55;
      margin: 7px auto 0;
      max-width: 460px;
    }

    .admin-empty-actions {
      align-items: center;
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      justify-content: center;
      margin-top: 16px;
    }

    .company-detail-grid,
    .admin-stat-grid {
      display: grid;
      gap: 12px;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      margin-bottom: 16px;
    }

    .company-detail-card,
    .admin-stat-card {
      background: var(--admin-gray-50);
      border: 1px solid var(--admin-gray-200);
      border-radius: var(--admin-radius);
      box-shadow: 0 8px 20px rgba(17, 24, 39, .05);
      padding: 14px;
    }

    .company-detail-label {
      color: var(--admin-gray-500);
      font-size: 12px;
      font-weight: 600;
    }

    .company-detail-value {
      color: var(--admin-gray-900);
      font-size: 18px;
      font-weight: 700;
      margin-top: 4px;
    }

    .form-row-2 {
      display: grid;
      gap: 10px;
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .admin-badge.published,
    .admin-badge.active,
    .admin-badge.approved {
      background: #ECFDF3;
      color: #067647;
    }

    .admin-badge.draft,
    .admin-badge.review,
    .admin-badge.pending {
      background: #FFFAEB;
      color: #B54708;
    }

    .admin-badge.archived,
    .admin-badge.inactive,
    .admin-badge.deleted {
      background: var(--gb-red-soft);
      color: var(--gb-red);
    }

    .admin-flash {
      background: #fff;
      border: 1px solid #BBF7D0;
      border-radius: 10px;
      box-shadow: 0 18px 40px rgba(17, 24, 39, .12);
      color: #067647;
      font-size: 13px;
      font-weight: 700;
      margin-bottom: 18px;
      padding: 13px 16px;
    }

    .admin-flash::before {
      background: #12B76A;
      border-radius: 50%;
      content: "";
      display: inline-block;
      height: 8px;
      margin-right: 9px;
      vertical-align: 1px;
      width: 8px;
    }

    .admin-mobile-bar,
    .admin-menu-backdrop {
      display: none;
    }

    .admin-menu-button,
    .admin-menu-close {
      align-items: center;
      background: #fff;
      border: 1px solid var(--gb-line);
      border-radius: 10px;
      color: #374151;
      display: none;
      height: 38px;
      justify-content: center;
      width: 38px;
    }

    .admin-menu-button:hover,
    .admin-menu-close:hover {
      background: var(--gb-soft);
      border-color: rgba(6, 16, 90, .2);
      color: var(--gb-green);
    }

    .admin-menu-close {
      margin-left: auto;
    }

    .admin-mobile-logo {
      display: block;
      width: 84px;
    }

    .admin-mobile-logo img {
      display: block;
      height: auto;
      width: 100%;
    }

    .admin-topbar form {
      margin: 0;
    }

    .pagination {
      gap: 6px;
      margin: 16px 0 0;
    }

    .page-link {
      border-color: var(--gb-line);
      border-radius: 8px;
      color: var(--gb-green);
      font-size: 12px;
      font-weight: 700;
    }

    .page-item.active .page-link {
      background: var(--gb-green);
      border-color: var(--gb-green);
    }

    .form-check-input:checked {
      background-color: var(--gb-green);
      border-color: var(--gb-green);
    }

    @media (max-width: 960px) {
      body.admin-menu-open {
        overflow: hidden;
      }

      .admin-mobile-bar {
        align-items: center;
        background: rgba(255, 255, 255, .96);
        border-bottom: 1px solid var(--gb-line);
        display: flex;
        gap: 14px;
        justify-content: space-between;
        min-height: 58px;
        padding: 10px 16px;
        position: sticky;
        top: 0;
        z-index: 60;
      }

      .admin-shell {
        display: block;
        min-height: 100vh;
      }

      .admin-menu-button,
      .admin-menu-close {
        display: inline-flex;
      }

      .admin-menu-backdrop {
        background: rgba(17, 24, 39, .42);
        display: block;
        inset: 0;
        opacity: 0;
        pointer-events: none;
        position: fixed;
        transition: opacity .2s ease;
        z-index: 75;
      }

      body.admin-menu-open .admin-menu-backdrop {
        opacity: 1;
        pointer-events: auto;
      }

      .admin-sidebar {
        box-shadow: 24px 0 60px rgba(15, 23, 42, .22);
        height: 100dvh;
        inset: 0 auto 0 0;
        max-width: calc(100vw - 38px);
        position: fixed;
        transform: translateX(-105%);
        transition: transform .24s cubic-bezier(.2, .8, .2, 1);
        width: min(332px, calc(100vw - 38px));
        z-index: 80;
      }

      .admin-sidebar.is-open {
        transform: translateX(0);
      }

      .admin-sidebar-brand {
        min-height: 66px;
        padding: 14px 14px 12px 18px;
      }

      .admin-main {
        min-height: 0;
        overflow: visible;
      }

      .admin-topbar {
        padding: 16px 18px;
      }

      .admin-content {
        overflow: visible;
        padding: 18px;
      }

      .admin-panel,
      .admin-card {
        padding: 16px;
      }

      .admin-topbar {
        align-items: flex-start;
      }
    }

    @media (max-width: 620px) {
      .admin-topbar {
        flex-direction: column;
      }

      .admin-topbar form,
      .admin-topbar .admin-btn-secondary {
        width: 100%;
      }

      .admin-content {
        padding: 14px;
      }

      .admin-page-title {
        font-size: 18px;
      }
    }
  </style>

  @stack('styles')
</head>

<body>
  <header class="admin-mobile-bar">
    <a class="admin-mobile-logo" href="{{ route('admin.dashboard') }}" aria-label="Go to dashboard" data-admin-spa-link>
      <img src="{{ asset('images/sif-logo-new.png') }}" alt="The Social Investment Fund">
    </a>
    <button class="admin-menu-button" type="button" aria-label="Open admin menu" aria-controls="admin-sidebar" aria-expanded="false" data-admin-menu-open>
      <i class="bi bi-list"></i>
    </button>
  </header>
  <div class="admin-menu-backdrop" data-admin-menu-close></div>

  <div class="admin-shell">
    <aside class="admin-sidebar" id="admin-sidebar">
      <div class="admin-sidebar-brand">
        <img src="{{ asset('images/sif-logo-new.png') }}" alt="The Social Investment Fund" class="admin-sidebar-logo">
        <button class="admin-menu-close" type="button" aria-label="Close admin menu" data-admin-menu-close>
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <nav class="admin-nav" aria-label="Admin navigation">
        @can('dashboard.view')
          <div class="admin-nav-group">
            <div class="admin-nav-list">
              <a href="{{ route('admin.dashboard') }}" class="admin-nav-link @if(request()->routeIs('admin.dashboard')) active @endif" data-admin-spa-link>
                <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
              </a>
            </div>
          </div>
        @endcan

        @if(auth()->user()->hasPermission('pages.view') || auth()->user()->hasPermission('posts.view') || auth()->user()->hasPermission('projects.view') || auth()->user()->hasPermission('faqs.view') || auth()->user()->hasPermission('impact.view') || auth()->user()->hasPermission('categories.view') || auth()->user()->hasPermission('press.view') || auth()->user()->hasPermission('notices.view'))
        <div class="admin-nav-group">
          <div class="admin-nav-section">Content</div>
          <div class="admin-nav-list">
            @can('pages.view')
            <a href="{{ route('admin.pages.index') }}" class="admin-nav-link @if(request()->routeIs('admin.pages.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-file-earmark-text"></i> <span>Pages</span>
            </a>
            @endcan
            @can('posts.view')
            <a href="{{ route('admin.posts.index') }}" class="admin-nav-link @if(request()->routeIs('admin.posts.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-newspaper"></i> <span>Posts</span>
            </a>
            @endcan
            @can('projects.view')
            <a href="{{ route('admin.projects.index') }}" class="admin-nav-link @if(request()->routeIs('admin.projects.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-kanban"></i> <span>Projects</span>
            </a>
            @endcan
            @can('faqs.view')
            <a href="{{ route('admin.faqs.index') }}" class="admin-nav-link @if(request()->routeIs('admin.faqs.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-question-circle"></i> <span>FAQs</span>
            </a>
            @endcan
            @can('impact.view')
            <a href="{{ route('admin.impact-metrics.index') }}" class="admin-nav-link @if(request()->routeIs('admin.impact-metrics.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-bar-chart-line"></i> <span>Impact</span>
            </a>
            @endcan
            @can('categories.view')
            <a href="{{ route('admin.categories.index') }}" class="admin-nav-link @if(request()->routeIs('admin.categories.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-tags"></i> <span>Categories</span>
            </a>
            @endcan
            @can('press.view')
            <a href="{{ route('admin.press-releases.index') }}" class="admin-nav-link @if(request()->routeIs('admin.press-releases.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-megaphone"></i> <span>Press Releases</span>
            </a>
            @endcan
            @can('notices.view')
            <a href="{{ route('admin.notices.index') }}" class="admin-nav-link @if(request()->routeIs('admin.notices.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-pin-angle"></i> <span>Notices</span>
            </a>
            @endcan
          </div>
        </div>
        @endif

        @if(auth()->user()->hasPermission('videos.view') || auth()->user()->hasPermission('gallery.view') || auth()->user()->hasPermission('graphics.view') || auth()->user()->hasPermission('media.view'))
        <div class="admin-nav-group">
          <div class="admin-nav-section">Media</div>
          <div class="admin-nav-list">
            @can('videos.view')
            <a href="{{ route('admin.videos.index') }}" class="admin-nav-link @if(request()->routeIs('admin.videos.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-play-btn"></i> <span>Videos</span>
            </a>
            @endcan
            @can('gallery.view')
            <a href="{{ route('admin.gallery.index') }}" class="admin-nav-link @if(request()->routeIs('admin.gallery.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-grid-3x3-gap"></i> <span>Gallery</span>
            </a>
            @endcan
            @can('graphics.view')
            <a href="{{ route('admin.graphics.index') }}" class="admin-nav-link @if(request()->routeIs('admin.graphics.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-palette"></i> <span>Graphics</span>
            </a>
            @endcan
            @can('media.view')
            <a href="{{ route('admin.media.index') }}" class="admin-nav-link @if(request()->routeIs('admin.media.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-images"></i> <span>Media Library</span>
            </a>
            @endcan
          </div>
        </div>
        @endif

        @can('people.view')
        <div class="admin-nav-group">
          <div class="admin-nav-section">People</div>
          <div class="admin-nav-list">
            <a href="{{ route('admin.people.index', 'board') }}" class="admin-nav-link @if(request()->is('admin/people/board*')) active @endif" data-admin-spa-link>
              <i class="bi bi-person-vcard"></i> <span>Board of Directors</span>
            </a>
            <a href="{{ route('admin.people.index', 'management') }}" class="admin-nav-link @if(request()->is('admin/people/management*')) active @endif" data-admin-spa-link>
              <i class="bi bi-diagram-3"></i> <span>Management Team</span>
            </a>
          </div>
        </div>
        @endcan

        @can('documents.view')
        <div class="admin-nav-group">
          <div class="admin-nav-section">Repository</div>
          <div class="admin-nav-list">
            <a href="{{ route('admin.documents.index', 'publications') }}" class="admin-nav-link @if(request()->is('admin/documents/publications*')) active @endif" data-admin-spa-link>
              <i class="bi bi-journal-text"></i> <span>Publications</span>
            </a>
            <a href="{{ route('admin.documents.index', 'annual-reports') }}" class="admin-nav-link @if(request()->is('admin/documents/annual-reports*')) active @endif" data-admin-spa-link>
              <i class="bi bi-file-earmark-bar-graph"></i> <span>Annual Reports</span>
            </a>
            <a href="{{ route('admin.documents.index', 'procurement-notices') }}" class="admin-nav-link @if(request()->is('admin/documents/procurement-notices*')) active @endif" data-admin-spa-link>
              <i class="bi bi-lightning-charge"></i> <span>Procurement Notices</span>
            </a>
            <a href="{{ route('admin.documents.index', 'environmental-social-documents') }}" class="admin-nav-link @if(request()->is('admin/documents/environmental-social-documents*')) active @endif" data-admin-spa-link>
              <i class="bi bi-globe2"></i> <span>Environmental &amp; Social</span>
            </a>
            <a href="{{ route('admin.documents.index', 'policies-downloads') }}" class="admin-nav-link @if(request()->is('admin/documents/policies-downloads*')) active @endif" data-admin-spa-link>
              <i class="bi bi-shield-check"></i> <span>Policies &amp; Downloads</span>
            </a>
          </div>
        </div>
        @endcan

        <div class="admin-nav-group">
          <div class="admin-nav-section">System</div>
          <div class="admin-nav-list">
            @can('users.view')
            <a href="{{ route('admin.users.index') }}" class="admin-nav-link @if(request()->routeIs('admin.users.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-people"></i> <span>Users</span>
            </a>
            @endcan
            @can('roles.view')
            <a href="{{ route('admin.roles.index') }}" class="admin-nav-link @if(request()->routeIs('admin.roles.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-shield-lock"></i> <span>Roles</span>
            </a>
            @endcan
            @can('activity.view')
            <a href="{{ route('admin.activity-logs.index') }}" class="admin-nav-link @if(request()->routeIs('admin.activity-logs.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-activity"></i> <span>Activity Log</span>
            </a>
            @endcan
            @can('trash.view')
            <a href="{{ route('admin.trash.index') }}" class="admin-nav-link @if(request()->routeIs('admin.trash.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-trash3"></i> <span>Trash</span>
            </a>
            @endcan
            @can('settings.view')
            <a href="{{ route('admin.settings.index') }}" class="admin-nav-link @if(request()->routeIs('admin.settings.*')) active @endif" data-admin-spa-link>
              <i class="bi bi-sliders"></i> <span>Settings</span>
            </a>
            @endcan
            <a href="{{ route('home') }}" class="admin-nav-link admin-nav-external" target="_blank" rel="noopener">
              <i class="bi bi-box-arrow-up-right"></i> <span>View Website</span>
            </a>
          </div>
        </div>
      </nav>

      <div class="admin-sidebar-footer">
        <div class="admin-user-row" title="{{ auth()->user()->email }}">
          <span class="admin-user-avatar">
            {{ collect(explode(' ', auth()->user()->name ?? 'Admin'))->filter()->map(fn ($part) => substr($part, 0, 1))->take(2)->implode('') ?: 'A' }}
          </span>
          <span style="min-width:0">
            <span class="admin-user-name">{{ auth()->user()->name ?? 'Administrator' }}</span>
            <span class="admin-user-role">{{ auth()->user()->email }}</span>
          </span>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit" class="admin-btn-secondary admin-sidebar-logout"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
      </div>
    </aside>

    <main class="admin-main">
      <div class="admin-topbar">
        <div>
          <h1 class="admin-page-title">@yield('page_title', 'Dashboard')</h1>
          <p class="admin-subtitle">@yield('page_subtitle', 'Manage SIF website operations.')</p>
        </div>

        <div class="admin-topbar-actions">
          <a href="{{ route('home') }}" class="admin-btn-secondary" target="_blank" rel="noopener">
            <i class="bi bi-box-arrow-up-right"></i> View Website
          </a>
        </div>
      </div>

      <div class="admin-content">
        @if(session('status'))
          <div class="admin-flash">{{ session('status') }}</div>
        @endif

        @yield('content')
      </div>
    </main>
  </div>

  <script data-admin-core-script>
    (() => {
      const body = document.body;
      const sidebar = document.getElementById('admin-sidebar');
      const openButton = document.querySelector('[data-admin-menu-open]');
      const closeButtons = document.querySelectorAll('[data-admin-menu-close]');
      const main = document.querySelector('.admin-main');
      const topbar = document.querySelector('.admin-topbar');
      const content = document.querySelector('.admin-content');
      const navLinks = document.querySelectorAll('[data-admin-spa-link]');
      let currentController = null;

      const setMenu = (open) => {
        body.classList.toggle('admin-menu-open', open);
        sidebar?.classList.toggle('is-open', open);
        openButton?.setAttribute('aria-expanded', open ? 'true' : 'false');
      };

      const sameOriginAdminUrl = (href) => {
        try {
          const url = new URL(href, window.location.href);
          return url.origin === window.location.origin && url.pathname.startsWith('/admin');
        } catch (error) {
          return false;
        }
      };

      const setActiveNav = (url) => {
        const current = new URL(url, window.location.origin);
        let bestMatch = null;

        navLinks.forEach((link) => {
          link.classList.remove('active');
          const linkUrl = new URL(link.href, window.location.origin);
          if (current.pathname === linkUrl.pathname || current.pathname.startsWith(`${linkUrl.pathname}/`)) {
            if (!bestMatch || linkUrl.pathname.length > new URL(bestMatch.href, window.location.origin).pathname.length) {
              bestMatch = link;
            }
          }
        });

        bestMatch?.classList.add('active');
      };

      const syncDynamicHead = (doc) => {
        document.querySelectorAll('[data-admin-spa-style]').forEach((node) => node.remove());
        Array.from(doc.head.querySelectorAll('style')).slice(1).forEach((style) => {
          const clone = style.cloneNode(true);
          clone.setAttribute('data-admin-spa-style', '');
          document.head.appendChild(clone);
        });
      };

      const runPageScripts = (doc) => {
        document.querySelectorAll('script[data-admin-spa-script]').forEach((node) => node.remove());
        doc.body.querySelectorAll('script:not([data-admin-core-script])').forEach((script) => {
          const clone = document.createElement('script');
          Array.from(script.attributes).forEach((attribute) => clone.setAttribute(attribute.name, attribute.value));
          clone.setAttribute('data-admin-spa-script', '');
          clone.textContent = script.textContent;
          document.body.appendChild(clone);
        });
      };

      const replaceMain = (doc) => {
        const nextTopbar = doc.querySelector('.admin-topbar');
        const nextContent = doc.querySelector('.admin-content');

        if (!nextTopbar || !nextContent || !topbar || !content) {
          return false;
        }

        topbar.innerHTML = nextTopbar.innerHTML;
        content.innerHTML = nextContent.innerHTML;
        content.scrollTop = 0;
        document.title = doc.title;
        syncDynamicHead(doc);
        runPageScripts(doc);
        return true;
      };

      const loadAdminPage = async (href, options = {}) => {
        if (!main || !sameOriginAdminUrl(href)) {
          window.location.href = href;
          return;
        }

        currentController?.abort();
        const controller = new AbortController();
        currentController = controller;
        main.classList.add('is-loading');

        try {
          const response = await fetch(href, {
            credentials: 'same-origin',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'text/html',
            },
            signal: controller.signal,
          });

          if (!response.ok || response.redirected) {
            window.location.href = response.url || href;
            return;
          }

          const html = await response.text();
          const doc = new DOMParser().parseFromString(html, 'text/html');

          if (!replaceMain(doc)) {
            window.location.href = href;
            return;
          }

          if (!options.replace) {
            window.history.pushState({ adminSpa: true }, doc.title, href);
          }

          setActiveNav(href);
          setMenu(false);
        } catch (error) {
          if (error.name !== 'AbortError') {
            window.location.href = href;
          }
        } finally {
          if (currentController === controller) {
            main.classList.remove('is-loading');
          }
        }
      };

      openButton?.addEventListener('click', () => setMenu(true));
      closeButtons.forEach((button) => button.addEventListener('click', () => setMenu(false)));
      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
          setMenu(false);
        }
      });

      document.addEventListener('click', (event) => {
        const link = event.target.closest('[data-admin-spa-link]');

        if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || link.target) {
          return;
        }

        if (!sameOriginAdminUrl(link.href)) {
          return;
        }

        event.preventDefault();

        if (new URL(link.href).href === window.location.href) {
          setMenu(false);
          return;
        }

        loadAdminPage(link.href);
      });

      window.addEventListener('popstate', () => {
        loadAdminPage(window.location.href, { replace: true });
      });

      window.history.replaceState({ adminSpa: true }, document.title, window.location.href);
    })();
  </script>

  @stack('scripts')
</body>

</html>
