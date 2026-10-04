<?php

$sourceDir = $argv[1] ?? '/private/tmp/sif-scrape';
$outputPath = $argv[2] ?? __DIR__.'/../database/data/sif_live_content.json';
$baseUrl = 'https://sifinghana.org/';

$sources = [
    ['file' => 'page-01.html', 'url' => 'index.php', 'type' => 'home'],
    ['file' => 'page-02.html', 'url' => 'about.php', 'type' => 'page', 'template' => 'about'],
    ['file' => 'page-03.html', 'url' => 'our-services.php', 'type' => 'page', 'template' => 'default'],
    ['file' => 'page-04.html', 'url' => 'board-of-directors.php', 'type' => 'page', 'template' => 'board'],
    ['file' => 'page-05.html', 'url' => 'executive-director.php', 'type' => 'page', 'template' => 'leadership'],
    ['file' => 'page-06.html', 'url' => 'sif-management.php', 'type' => 'people'],
    ['file' => 'page-07.html', 'url' => 'page.php?cat_id=2116', 'type' => 'page', 'template' => 'department'],
    ['file' => 'page-08.html', 'url' => 'page.php?cat_id=2117', 'type' => 'page', 'template' => 'department'],
    ['file' => 'page-09.html', 'url' => 'page.php?cat_id=2118', 'type' => 'page', 'template' => 'department'],
    ['file' => 'page-10.html', 'url' => 'page.php?cat_id=2119', 'type' => 'page', 'template' => 'department'],
    ['file' => 'page-11.html', 'url' => 'page.php?cat_id=2120', 'type' => 'page', 'template' => 'department'],
    ['file' => 'page-12.html', 'url' => 'page.php?cat_id=2122', 'type' => 'page', 'template' => 'department'],
    ['file' => 'page-13.html', 'url' => 'page.php?cat_id=2123', 'type' => 'page', 'template' => 'department'],
    ['file' => 'page-14.html', 'url' => 'page.php?cat_id=2124', 'type' => 'page', 'template' => 'department'],
    ['file' => 'page-15.html', 'url' => 'news.php', 'type' => 'news-index'],
    ['file' => 'page-16.html', 'url' => 'publications.php', 'type' => 'documents', 'document_type' => 'publications'],
    ['file' => 'page-17.html', 'url' => 'annual-reports.php', 'type' => 'documents', 'document_type' => 'annual-reports'],
    ['file' => 'page-18.html', 'url' => 'contact.php', 'type' => 'page', 'template' => 'contact'],
    ['file' => 'page-19.html', 'url' => 'complains.php', 'type' => 'page', 'template' => 'complaint'],
    ['file' => 'page-20.html', 'url' => 'project-detail.php?cat_id=2216', 'type' => 'project'],
    ['file' => 'page-21.html', 'url' => 'project-detail.php?cat_id=2156', 'type' => 'project'],
    ['file' => 'page-22.html', 'url' => 'project-detail.php?cat_id=2158', 'type' => 'project'],
    ['file' => 'page-23.html', 'url' => 'project-detail.php?cat_id=2155', 'type' => 'project'],
    ['file' => 'page-24.html', 'url' => 'project-detail.php?cat_id=2157', 'type' => 'project'],
    ['file' => 'page-25.html', 'url' => 'project-detail.php?cat_id=2219', 'type' => 'project'],
    ['file' => 'page-26.html', 'url' => 'committee.php?cat_id=2183', 'type' => 'page', 'template' => 'committee'],
    ['file' => 'page-27.html', 'url' => 'committee.php?cat_id=2184', 'type' => 'page', 'template' => 'committee'],
];

for ($i = 28; $i <= 41; $i++) {
    $ids = [28 => 3287, 29 => 3286, 30 => 3285, 31 => 3284, 32 => 3283, 33 => 3282, 34 => 3281, 35 => 3280, 36 => 3279, 37 => 3278, 38 => 3255, 39 => 3254, 40 => 3253, 41 => 3234];
    $sources[] = ['file' => sprintf('page-%02d.html', $i), 'url' => 'page.php?id='.$ids[$i], 'type' => 'post', 'source_id' => (string) $ids[$i]];
}

$galleryIds = ['1734642350', '1736884728', '1742625925', '1742625962', '1742626010', 'GPLQ1734627434', 'JDXP1732721030', 'MLPR1732723949'];
foreach ($galleryIds as $offset => $id) {
    $sources[] = ['file' => sprintf('page-%02d.html', 42 + $offset), 'url' => 'project-images.php?id='.$id, 'type' => 'gallery', 'source_id' => $id];
}

$data = [
    'source' => $baseUrl,
    'scraped_at' => gmdate('c'),
    'pages' => [],
    'posts' => [],
    'documents' => [],
    'people' => [],
    'projects' => [],
    'gallery_albums' => [],
    'graphics' => [],
];

foreach ($sources as $source) {
    $path = rtrim($sourceDir, '/').'/'.$source['file'];
    if (! is_file($path)) {
        continue;
    }

    [$dom, $xpath] = loadDom(file_get_contents($path));
    $url = absoluteUrl($source['url'], $baseUrl);

    if ($source['type'] === 'page') {
        $page = extractContentPage($dom, $xpath, $source, $url, $baseUrl);
        if ($page['title'] !== '') {
            $data['pages'][] = $page;
        }
        continue;
    }

    if ($source['type'] === 'post') {
        $post = extractContentPage($dom, $xpath, $source, $url, $baseUrl);
        if ($post['title'] !== '') {
            $data['posts'][] = [
                'title' => $post['title'],
                'slug' => slug($post['title'].'-'.$source['source_id']),
                'category' => classifyPost($post['title']),
                'excerpt' => excerpt($post['body_text'] ?: $post['title']),
                'body' => appendSource($post['body_html'] ?: '<p>'.$post['title'].'</p>', $url),
                'featured_image' => $post['images'][0]['url'] ?? null,
                'source_url' => $url,
                'source_id' => $source['source_id'],
            ];

            foreach (extractLinks($xpath, $baseUrl, $url) as $link) {
                if (isDocumentUrl($link['url'])) {
                    $data['documents'][] = documentPayload($post['title'], $link['url'], 'publications', $url, $post['title']);
                }
            }
        }
        continue;
    }

    if ($source['type'] === 'documents') {
        foreach (extractDownloadDocuments($xpath, $source['document_type'], $url, $baseUrl) as $document) {
            $data['documents'][] = $document;
        }
        continue;
    }

    if ($source['type'] === 'people') {
        $data['people'] = array_merge($data['people'], extractPeople($xpath, $baseUrl));
        continue;
    }

    if ($source['type'] === 'project') {
        $project = extractProject($dom, $xpath, $source, $url, $baseUrl);
        if ($project['name'] !== '') {
            $data['projects'][] = $project;
        }
        foreach (extractLinks($xpath, $baseUrl, $url) as $link) {
            if (isDocumentUrl($link['url'])) {
                $data['documents'][] = documentPayload($project['name'], $link['url'], 'publications', $url, $project['name']);
            }
        }
        continue;
    }

    if ($source['type'] === 'gallery') {
        $album = extractGalleryAlbum($dom, $xpath, $source, $url, $baseUrl);
        if ($album['title'] !== '' && $album['images'] !== []) {
            $data['gallery_albums'][] = $album;
        }
    }
}

$home = loadDom(file_get_contents(rtrim($sourceDir, '/').'/page-01.html'));
foreach (extractHomeGraphics($home[1], $baseUrl) as $graphic) {
    $data['graphics'][] = $graphic;
}

$data['posts'] = uniqueBy($data['posts'], 'slug');
$data['documents'] = uniqueBy($data['documents'], 'slug');
$data['pages'] = uniqueBy($data['pages'], 'slug');
$data['people'] = uniqueBy($data['people'], 'slug');
$data['projects'] = uniqueBy($data['projects'], 'slug');
$data['gallery_albums'] = uniqueBy($data['gallery_albums'], 'slug');
$data['graphics'] = uniqueBy($data['graphics'], 'slug');

if (! is_dir(dirname($outputPath))) {
    mkdir(dirname($outputPath), 0775, true);
}

file_put_contents($outputPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).PHP_EOL);

printf(
    "Wrote %s: %d pages, %d posts, %d documents, %d people, %d projects, %d gallery albums, %d graphics\n",
    $outputPath,
    count($data['pages']),
    count($data['posts']),
    count($data['documents']),
    count($data['people']),
    count($data['projects']),
    count($data['gallery_albums']),
    count($data['graphics'])
);

function loadDom(string $html): array
{
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML('<?xml encoding="utf-8" ?>'.$html);

    return [$dom, new DOMXPath($dom)];
}

function extractContentPage(DOMDocument $dom, DOMXPath $xpath, array $source, string $url, string $baseUrl): array
{
    absolutizeDom($xpath, $baseUrl);

    $title = text($xpath, '//*[@id="contact_head"]') ?: text($xpath, '//h1[1]') ?: meta($xpath, 'og:title');
    $bodyNode = first($xpath, '//*[@id="page_main_content"]');
    $bodyHtml = $bodyNode ? innerHtml($dom, $bodyNode) : '';

    foreach ($xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " accordion-body ")]') as $accordion) {
        $bodyHtml .= "\n".innerHtml($dom, $accordion);
    }

    $bodyText = trimText(strip_tags($bodyHtml));
    $images = extractImages($xpath, $baseUrl, $bodyNode ? './/*[@src]' : '//*[@src]', $bodyNode);
    $template = $source['template'] ?? 'default';
    $slugBase = $template === 'department' || $template === 'committee'
        ? $title.'-'.$source['url']
        : $title;

    return [
        'title' => $title,
        'slug' => slug($slugBase),
        'template' => $template,
        'excerpt' => excerpt($bodyText ?: $title),
        'body_html' => appendSource($bodyHtml ?: '<p>'.$title.'</p>', $url),
        'body_text' => $bodyText,
        'seo_title' => $title.' | SIF Ghana',
        'seo_description' => excerpt($bodyText ?: $title),
        'source_url' => $url,
        'images' => $images,
    ];
}

function extractProject(DOMDocument $dom, DOMXPath $xpath, array $source, string $url, string $baseUrl): array
{
    $page = extractContentPage($dom, $xpath, ['template' => 'project'] + $source, $url, $baseUrl);
    $name = normalizeProjectName($page['title']);

    return [
        'name' => $name,
        'full_name' => $name,
        'slug' => slug($name),
        'summary' => $page['excerpt'],
        'body' => $page['body_html'],
        'image' => $page['images'][0]['url'] ?? null,
        'source_url' => $url,
    ];
}

function extractPeople(DOMXPath $xpath, string $baseUrl): array
{
    $people = [];

    foreach ($xpath->query('//*[@id="management_imgs"]') as $img) {
        $name = trimText($img->getAttribute('alt'));
        if ($name === '' || strtolower($name) === 'the social investment fund') {
            continue;
        }

        $card = $img->parentNode;
        $position = textFromNode(firstFromNode($xpath, $card, './/*[@id="profile_title"]'));
        $bio = '';

        foreach ($xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " modal ")]') as $modal) {
            if (textFromNode(firstFromNode($xpath, $modal, './/*[@id="exampleModalLabel"]')) === $name) {
                $body = firstFromNode($xpath, $modal, './/*[contains(concat(" ", normalize-space(@class), " "), " modal-body ")]');
                $bio = $body ? innerHtml($modal->ownerDocument, $body) : '';
                break;
            }
        }

        $plainBio = trimText(strip_tags($bio));
        $people[] = [
            'group' => 'management',
            'name' => $name,
            'slug' => slug($name),
            'position' => normalizeCase($position),
            'brief_profile' => excerpt($plainBio ?: $position),
            'bio' => absolutizeHtml($bio, $baseUrl),
            'photo_path' => absoluteUrl($img->getAttribute('src'), $baseUrl),
        ];
    }

    return $people;
}

function extractDownloadDocuments(DOMXPath $xpath, string $type, string $sourceUrl, string $baseUrl): array
{
    $documents = [];

    foreach ($xpath->query('//a[@href]') as $link) {
        $href = absoluteUrl($link->getAttribute('href'), $baseUrl);
        if (! isDocumentUrl($href)) {
            continue;
        }

        $label = trimText($link->parentNode?->parentNode?->textContent ?? $link->textContent);
        $label = preg_replace('/\bDownload\b/i', '', $label);
        $title = trimText($label) ?: basename(parse_url($href, PHP_URL_PATH));
        $documents[] = documentPayload($title, $href, $type, $sourceUrl, $title);
    }

    return $documents;
}

function extractGalleryAlbum(DOMDocument $dom, DOMXPath $xpath, array $source, string $url, string $baseUrl): array
{
    $title = text($xpath, '//*[@id="contact_head"]') ?: text($xpath, '//h1[1]') ?: 'SIF Gallery '.$source['source_id'];
    $images = extractImages($xpath, $baseUrl);
    $images = array_values(array_filter($images, fn ($image) => ! str_contains($image['url'], 'logo')));

    return [
        'title' => $title,
        'slug' => slug($title.'-'.$source['source_id']),
        'description' => 'Images scraped from '.$url,
        'cover_image_path' => $images[0]['url'] ?? null,
        'source_url' => $url,
        'images' => $images,
    ];
}

function extractHomeGraphics(DOMXPath $xpath, string $baseUrl): array
{
    $graphics = [];

    foreach (extractImages($xpath, $baseUrl) as $image) {
        if (str_contains($image['url'], 'logo') || $image['alt'] === '' || strtolower($image['alt']) === 'slider') {
            continue;
        }

        $graphics[] = [
            'title' => $image['alt'],
            'slug' => slug($image['alt'].'-'.basename(parse_url($image['url'], PHP_URL_PATH))),
            'description' => 'Image scraped from the live SIF Ghana homepage.',
            'image_path' => $image['url'],
            'alt_text' => $image['alt'],
        ];
    }

    return $graphics;
}

function extractImages(DOMXPath $xpath, string $baseUrl, string $query = '//*[@src]', ?DOMNode $context = null): array
{
    $images = [];
    $nodes = $context ? $xpath->query($query, $context) : $xpath->query($query);

    foreach ($nodes as $img) {
        if ($img->nodeName !== 'img') {
            continue;
        }

        $src = trim($img->getAttribute('src'));
        if ($src === '') {
            continue;
        }

        $images[] = [
            'url' => absoluteUrl($src, $baseUrl),
            'alt' => trimText($img->getAttribute('alt')),
        ];
    }

    return uniqueBy($images, 'url');
}

function extractLinks(DOMXPath $xpath, string $baseUrl): array
{
    $links = [];
    foreach ($xpath->query('//a[@href]') as $link) {
        $links[] = [
            'url' => absoluteUrl($link->getAttribute('href'), $baseUrl),
            'text' => trimText($link->textContent),
        ];
    }

    return uniqueBy($links, 'url');
}

function documentPayload(string $title, string $url, string $type, string $sourceUrl, string $fallbackTitle): array
{
    $cleanTitle = trimText($title) ?: $fallbackTitle;
    $path = parse_url($url, PHP_URL_PATH) ?: '';

    return [
        'type' => $type,
        'title' => $cleanTitle,
        'slug' => slug($cleanTitle.'-'.basename($path)),
        'brief_description' => 'Scraped from '.$sourceUrl,
        'file_path' => $url,
        'file_name' => basename($path) ?: $cleanTitle,
        'file_mime_type' => str_ends_with(strtolower($path), '.pdf') ? 'application/pdf' : null,
        'source_url' => $sourceUrl,
    ];
}

function absolutizeDom(DOMXPath $xpath, string $baseUrl): void
{
    foreach ($xpath->query('//*[@src]') as $node) {
        $node->setAttribute('src', absoluteUrl($node->getAttribute('src'), $baseUrl));
    }

    foreach ($xpath->query('//*[@href]') as $node) {
        $node->setAttribute('href', absoluteUrl($node->getAttribute('href'), $baseUrl));
    }
}

function absolutizeHtml(string $html, string $baseUrl): string
{
    [$dom, $xpath] = loadDom('<div id="wrap">'.$html.'</div>');
    absolutizeDom($xpath, $baseUrl);
    $wrap = first($xpath, '//*[@id="wrap"]');

    return $wrap ? innerHtml($dom, $wrap) : $html;
}

function innerHtml(DOMDocument $dom, DOMNode $node): string
{
    $html = '';
    foreach ($node->childNodes as $child) {
        $html .= $dom->saveHTML($child);
    }

    return trim($html);
}

function first(DOMXPath $xpath, string $query): ?DOMNode
{
    $nodes = $xpath->query($query);
    return $nodes && $nodes->length ? $nodes->item(0) : null;
}

function firstFromNode(DOMXPath $xpath, DOMNode $node, string $query): ?DOMNode
{
    $nodes = $xpath->query($query, $node);
    return $nodes && $nodes->length ? $nodes->item(0) : null;
}

function text(DOMXPath $xpath, string $query): string
{
    return textFromNode(first($xpath, $query));
}

function textFromNode(?DOMNode $node): string
{
    return $node ? trimText($node->textContent) : '';
}

function meta(DOMXPath $xpath, string $property): string
{
    $node = first($xpath, '//meta[@property="'.$property.'"]/@content');
    return $node ? trimText($node->nodeValue) : '';
}

function trimText(string $text): string
{
    return trim(preg_replace('/\s+/u', ' ', html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
}

function excerpt(string $text, int $length = 220): string
{
    $text = trimText($text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }

    return rtrim(mb_substr($text, 0, $length - 1)).'…';
}

function slug(string $value): string
{
    $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;
    $value = strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $value));

    return trim($value, '-') ?: 'sif-content';
}

function absoluteUrl(string $url, string $baseUrl): string
{
    $url = trim($url);
    if ($url === '' || preg_match('/^(https?:|mailto:|tel:)/i', $url)) {
        return $url;
    }

    return rtrim($baseUrl, '/').'/'.ltrim($url, '/');
}

function appendSource(string $html, string $url): string
{
    return trim($html)."\n".'<p><small>Source: <a href="'.$url.'" target="_blank" rel="noopener">'.$url.'</a></small></p>';
}

function isDocumentUrl(string $url): bool
{
    return (bool) preg_match('/\.(pdf|doc|docx|xls|xlsx|jpg|jpeg|png|webp)(\?|$)/i', parse_url($url, PHP_URL_PATH) ?? $url);
}

function classifyPost(string $title): string
{
    $title = strtolower($title);

    return match (true) {
        str_contains($title, 'environmental'), str_contains($title, 'social') => 'Environmental & Social',
        str_contains($title, 'badea'), str_contains($title, 'ministry') => 'Partnerships',
        str_contains($title, 'contractor'), str_contains($title, 'site') => 'Community',
        default => 'Programme Updates',
    };
}

function normalizeProjectName(string $title): string
{
    $title = preg_replace('/^projects?\s*/i', '', trimText($title));

    return $title ?: 'SIF Project';
}

function normalizeCase(string $text): string
{
    $text = trimText($text);
    if ($text === mb_strtolower($text)) {
        return mb_convert_case($text, MB_CASE_TITLE, 'UTF-8');
    }

    return $text;
}

function uniqueBy(array $items, string $key): array
{
    $seen = [];
    $unique = [];

    foreach ($items as $item) {
        if (! isset($item[$key]) || $item[$key] === '' || isset($seen[$item[$key]])) {
            continue;
        }

        $seen[$item[$key]] = true;
        $unique[] = $item;
    }

    return $unique;
}
