<!DOCTYPE html>
<html lang="en">
<head>
  @php
    $seoTitle = trim($__env->yieldContent('title', 'The Social Investment Fund | SIF Ghana'));
    $seoDescription = trim($__env->yieldContent('description', 'The Social Investment Fund Ghana is a pro-poor Government of Ghana institution mobilising resources and delivering inclusive economic and social development programmes across Ghana.'));
    $seoBaseUrl = rtrim(config('app.seo_url', 'https://sifinghana.gov.gh'), '/');
    $currentPath = request()->getPathInfo();
    $defaultCanonical = $seoBaseUrl . ($currentPath === '/' ? '' : $currentPath);
    $normalizeSeoUrl = function ($value) use ($seoBaseUrl) {
      $value = trim($value);
      if ($value === '') {
        return $seoBaseUrl;
      }
      if (preg_match('#^https?://#i', $value)) {
        $host = parse_url($value, PHP_URL_HOST);
        $localHosts = ['localhost', '127.0.0.1'];
        $appHost = parse_url(config('app.url'), PHP_URL_HOST);
        if (in_array($host, $localHosts, true) || ($appHost && $host === $appHost)) {
          $path = parse_url($value, PHP_URL_PATH) ?: '';
          $query = parse_url($value, PHP_URL_QUERY);
          return $seoBaseUrl . $path . ($query ? '?' . $query : '');
        }
        return $value;
      }
      return $seoBaseUrl . '/' . ltrim($value, '/');
    };
    $seoCanonical = $normalizeSeoUrl($__env->yieldContent('canonical', $defaultCanonical));
    $seoImage = $normalizeSeoUrl($__env->yieldContent('seo_image', '/images/sif-og-image.png'));
    $seoType = trim($__env->yieldContent('seo_type', 'website'));
    $seoRobots = trim($__env->yieldContent('robots', 'index, follow'));
    $siteName = 'The Social Investment Fund Ghana';
    $siteRootUrl = $seoBaseUrl;
    $siteLogoUrl = $seoBaseUrl . '/images/sif-logo-new.png';
    $breadcrumbItems = [
      [
        '@type' => 'ListItem',
        'position' => 1,
        'name' => 'Home',
        'item' => $siteRootUrl,
      ],
    ];
    $breadcrumbPath = trim(request()->path(), '/');
    if ($breadcrumbPath !== '') {
      $labelMap = [
        'about' => 'About',
        'board-of-directors' => 'Board of Directors',
        'leadership' => 'Leadership',
        'departments' => 'Departments',
        'projects' => 'Projects',
        'news' => 'News',
        'resources' => 'Resources',
        'contact' => 'Contact',
        'complaint' => 'Complaint',
        'privacy' => 'Privacy Policy',
        'terms' => 'Terms of Use',
        'accessibility' => 'Accessibility',
        'sitemap' => 'Sitemap',
      ];
      $projectLabels = collect(config('sif_projects.projects', []))->mapWithKeys(fn ($project, $projectSlug) => [$projectSlug => $project['name'] ?? str($projectSlug)->headline()->toString()])->all();
      $segments = explode('/', $breadcrumbPath);
      $pathParts = [];
      foreach ($segments as $segment) {
        $pathParts[] = $segment;
        $label = $projectLabels[$segment] ?? $labelMap[$segment] ?? str_replace('-', ' ', str($segment)->headline()->toString());
        $breadcrumbItems[] = [
          '@type' => 'ListItem',
          'position' => count($breadcrumbItems) + 1,
          'name' => $label,
          'item' => $siteRootUrl . '/' . implode('/', $pathParts),
        ];
      }
    }
    $breadcrumbSchema = [
      '@context' => 'https://schema.org',
      '@type' => 'BreadcrumbList',
      'itemListElement' => $breadcrumbItems,
    ];
  @endphp
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $seoTitle }}</title>
  <meta name="description" content="{{ $seoDescription }}">
  <meta name="robots" content="{{ $seoRobots }}">
  <meta name="author" content="{{ $siteName }}">
  <meta name="application-name" content="SIF Ghana">
  <meta name="theme-color" content="#06105A">
  <link rel="canonical" href="{{ $seoCanonical }}">
  <meta property="og:locale" content="en_GH">
  <meta property="og:site_name" content="{{ $siteName }}">
  <meta property="og:type" content="{{ $seoType }}">
  <meta property="og:title" content="{{ $seoTitle }}">
  <meta property="og:description" content="{{ $seoDescription }}">
  <meta property="og:url" content="{{ $seoCanonical }}">
  <meta property="og:image" content="{{ $seoImage }}">
  <meta property="og:image:alt" content="The Social Investment Fund Ghana">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ $seoTitle }}">
  <meta name="twitter:description" content="{{ $seoDescription }}">
  <meta name="twitter:image" content="{{ $seoImage }}">
  <link rel="icon" type="image/png" href="{{ asset('images/sif-logo-new.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('images/sif-logo-new.png') }}">
  <script type="application/ld+json">
  {
    "@@context": "https://schema.org",
    "@@type": "GovernmentOrganization",
    "name": "The Social Investment Fund Ghana",
    "alternateName": "SIF Ghana",
    "url": "{{ $siteRootUrl }}",
    "logo": "{{ $siteLogoUrl }}",
    "address": {
      "@@type": "PostalAddress",
      "addressLocality": "Accra",
      "addressCountry": "GH"
    },
    "contactPoint": {
      "@@type": "ContactPoint",
      "telephone": "0800 600 555",
      "contactType": "customer service",
      "areaServed": "GH"
    }
  }
  </script>
  <script type="application/ld+json">
  {
    "@@context": "https://schema.org",
    "@@type": "WebSite",
    "name": "{{ $siteName }}",
    "url": "{{ $siteRootUrl }}",
    "potentialAction": {
      "@@type": "SearchAction",
      "target": "{{ $siteRootUrl }}?q={search_term_string}",
      "query-input": "required name=search_term_string"
    }
  }
  </script>
  @if(count($breadcrumbItems) > 1)
  <script type="application/ld+json">
  @json($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
  </script>
  @endif
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    .sticky-apply-cta{position:fixed;right:20px;bottom:20px;z-index:2000;display:inline-flex;align-items:center;justify-content:center;min-height:0;padding:12px 24px;border-radius:30px;background:var(--emerald);color:#fff;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,sans-serif;font-size:15px;font-weight:700;box-shadow:0 4px 8px rgba(0,0,0,.2);border:0;white-space:nowrap;text-decoration:none;cursor:pointer;transition:background-color .3s ease,transform .18s ease,box-shadow .18s ease;}
    .sticky-apply-cta:hover{background:var(--forest-2);text-decoration:none;transform:translateY(-2px);box-shadow:0 8px 18px rgba(0,0,0,.25);}
    @media (max-width:600px){.sticky-apply-cta{left:auto;right:8px;bottom:15px;width:112px;padding:10px 14px;font-size:14px;}}
    @media (max-width:600px){body.has-cookie-banner .sticky-apply-cta{bottom:182px;}}
  </style>
  @stack('head')
</head>
<body>
<a href="#main" class="skip-link">Skip to main content</a>
<x-header />
<span id="top-sentinel" style="position:absolute;top:0;height:1px;"></span>
<main id="main">
@yield('content')
</main>
<x-footer />
<a class="sticky-apply-cta" href="https://apply.sifinghana.gov.gh/" target="_blank" rel="noopener" aria-label="Apply Now">
  Apply Now
</a>
@stack('scripts')
</body>
</html>
