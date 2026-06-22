<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'The Social Investment Fund | SIF Ghana')</title>
  <meta name="description" content="@yield('description', 'A pro-poor institution of the Government of Ghana, mobilising resources and delivering inclusive economic and social development programmes.')">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='6' fill='%231a3a2a'/><text x='50%' y='55%' dominant-baseline='middle' text-anchor='middle' font-family='Arial' font-weight='900' font-size='18' fill='%23d4a843'>S</text></svg>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
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
@stack('scripts')
</body>
</html>
