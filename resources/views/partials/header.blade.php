<header class="gb-navbar">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand gb-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/Goldboard-Logo-white-Text-updated.webp') }}" alt="GoldBod">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#gbNav"
                aria-controls="gbNav" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="gbNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" data-page="index" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-page="about" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">About</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('pages.about') }}">Overview</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.organogram') }}">Organogram</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.board') }}">Board of Directors</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.management') }}">Management Team</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-page="policies" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Policies</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item"
                                    href="{{ route('pages.corporate-social-responsibility') }}">CSR</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.insurance-policy') }}">Insurance
                                    Policy</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.responsible-sourcing') }}">Responsible
                                    Sourcing</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-page="news" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">News &amp; Release</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('pages.news') }}">News</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.articles') }}">Articles</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.press') }}">Press Releases</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.notice') }}">Notice</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-page="media" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Media</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('pages.videos') }}">Videos</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.gallery') }}">Gallery</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.graphics') }}">Graphics</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-page="repository" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Repository</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ asset('file/GHANA-GOLDBOD-ACT-ACT-1140.pdf') }}"
                                    target="_blank" rel="noopener noreferrer">The Ghana Gold Board Act, 2025 (ACT
                                    1140)</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.license-registry') }}">License
                                    Registry</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ route('pages.audited-financial-statements') }}">Audited Financial
                                    Statements</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.quarterly-reports') }}">Quarterly
                                    Reports</a></li>
                            <li><a class="dropdown-item" href="{{ route('pages.trade-reports') }}">Trade Reports</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('pages.contracts') }}">Contracts</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-page="contact" href="{{ route('pages.contact') }}">Contact us</a>
                    </li>
                </ul>
                <a href="{{ route('pages.licensing') }}" class="btn btn-licensing ms-lg-3">Licensing</a>
            </div>
        </div>
    </nav>
</header>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-K3NVJP64MW"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-K3NVJP64MW');
</script>
