@php
    $categoryLabel = $post->categoryRelation?->name ?? $fallbackLabel;
    $publishedDate = $post->published_at?->format('F j, Y');
    $plainExcerpt = trim(strip_tags((string) $post->excerpt));
    $description = $post->seo_description ?: $plainExcerpt;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->seo_title ?: $post->title }} | GoldBod - Ghana Gold Board</title>
    @if ($description)
        <meta name="description" content="{{ $description }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    @include('partials.header')

    <main>
        <section class="article-single-section py-5 py-lg-6 globalspac">
    <div class="container py-lg-5">
        <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" class="article-hero-img mb-4">

        <div class="article-body">
            <div class="article-meta">
                @if ($publishedDate)
                    <span class="article-date">{{ $publishedDate }}</span>
                    <span class="article-sep">|</span>
                @endif
                <span class="article-tag">{{ $categoryLabel }}</span>
            </div>

            <h1 class="section-title article-title">{{ $post->title }}</h1>

            <div class="post-content">
                {!! $post->body ?: '<p>' . e($plainExcerpt) . '</p>' !!}
            </div>
        </div>
    </div>
</section>

        @if ($relatedPosts->isNotEmpty())
            <section class="related-news-section py-5 py-lg-6 globalspac">
                <div class="container py-4">
                    <h2 class="section-title mb-4">{{ $relatedTitle }}</h2>

                    <div class="row g-4">
                        @foreach ($relatedPosts as $relatedPost)
                            <div class="col-md-4">@include('partials.post-card', ['post' => $relatedPost])</div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

    @include('partials.footer')
    <a href="https://wa.me/+233553001670" class="whatsapp-fab" target="_blank" rel="noopener"
        aria-label="Chat with us on WhatsApp"><i class="bi bi-whatsapp"></i></a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.GB_NAV_SECTION = 'news';
    </script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
