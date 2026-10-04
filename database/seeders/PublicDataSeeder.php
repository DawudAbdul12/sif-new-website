<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CmsPage;
use App\Models\CmsPost;
use App\Models\Document;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Models\Graphic;
use App\Models\Notice;
use App\Models\Person;
use App\Models\PressRelease;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('users')->where('is_admin', true)->value('id');

        $categoryIds = $this->seedCategories($adminId);

        $this->seedPages($adminId, $now);
        $this->seedPosts($categoryIds, $adminId, $now);
        $this->seedPressReleases($adminId, $now);
        $this->seedNotices($adminId, $now);
        $this->seedDocuments($adminId, $now);
        $this->seedPeople($adminId, $now);
        $this->seedGallery($adminId, $now);
        $this->seedGraphics($adminId, $now);
        $this->seedVideos($adminId, $now);
    }

    private function seedCategories(?int $adminId): array
    {
        $categories = [
            ['name' => 'Programme Updates', 'slug' => 'programme-updates', 'type' => 'news', 'description' => 'Updates from SIF programmes and project delivery.', 'color' => '#087f5b', 'sort_order' => 10],
            ['name' => 'Partnerships', 'slug' => 'partnerships', 'type' => 'news', 'description' => 'Partner missions, funder engagements and institutional collaboration.', 'color' => '#17472d', 'sort_order' => 20],
            ['name' => 'Community', 'slug' => 'community', 'type' => 'news', 'description' => 'Field stories, handovers and beneficiary community updates.', 'color' => '#d6a72c', 'sort_order' => 30],
            ['name' => 'Environmental & Social', 'slug' => 'environmental-social', 'type' => 'news', 'description' => 'Safeguards, consultations and social performance updates.', 'color' => '#35605a', 'sort_order' => 40],
            ['name' => 'Procurement', 'slug' => 'procurement', 'type' => 'news', 'description' => 'Tender notices and procurement announcements.', 'color' => '#7a5a0e', 'sort_order' => 50],
            ['name' => 'Institutional News', 'slug' => 'institutional-news', 'type' => 'article', 'description' => 'Corporate and institutional updates from SIF Ghana.', 'color' => '#17472d', 'sort_order' => 60],
        ];

        $ids = [];

        foreach ($categories as $category) {
            $record = Category::query()->updateOrCreate(
                ['slug' => $category['slug']],
                $category + [
                    'status' => 'active',
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );

            $ids[$category['slug']] = $record->id;
        }

        return $ids;
    }

    private function seedPages(?int $adminId, $now): void
    {
        $pages = [
            ['title' => 'Home', 'slug' => 'home', 'template' => 'home', 'excerpt' => 'The Social Investment Fund Ghana official website.', 'body' => 'SIF Ghana supports poverty reduction, community infrastructure, job creation and programme delivery across Ghana.', 'seo_title' => 'SIF Ghana | Social Investment Fund', 'seo_description' => 'The official website of the Social Investment Fund Ghana.'],
            ['title' => 'About SIF Ghana', 'slug' => 'about', 'template' => 'about', 'excerpt' => 'Learn about the mandate, history and development role of SIF Ghana.', 'body' => 'The Social Investment Fund Ghana is a development finance and implementation institution supporting pro-poor infrastructure and social investment programmes.', 'seo_title' => 'About SIF Ghana', 'seo_description' => 'Learn about the mandate, history and development role of SIF Ghana.'],
            ['title' => 'Board of Directors', 'slug' => 'board-of-directors', 'template' => 'board', 'excerpt' => 'Independent oversight, strategic direction and institutional accountability.', 'body' => 'The Board of Directors provides strategic oversight for SIF, reviews institutional performance and supports accountability across the Fund.', 'seo_title' => 'Board of Directors | SIF Ghana Governance', 'seo_description' => 'Meet the Board of Directors of the Social Investment Fund Ghana.'],
            ['title' => 'Leadership', 'slug' => 'leadership', 'template' => 'leadership', 'excerpt' => 'SIF Ghana leadership and senior management team.', 'body' => 'SIF is led by executive management and a multidisciplinary senior team coordinating finance, procurement, monitoring, administration and zonal delivery.', 'seo_title' => 'Leadership | SIF Ghana', 'seo_description' => 'Meet the Social Investment Fund Ghana leadership team.'],
            ['title' => 'Departments and Zones', 'slug' => 'departments', 'template' => 'departments', 'excerpt' => 'Departments, units and zonal offices supporting SIF delivery.', 'body' => 'SIF departments and zonal offices coordinate programme delivery, field support and institutional operations across Ghana.', 'seo_title' => 'Departments and Zones | SIF Ghana', 'seo_description' => 'Explore SIF Ghana departments, units and zonal offices.'],
            ['title' => 'Projects', 'slug' => 'projects', 'template' => 'projects', 'excerpt' => 'Flagship programmes and projects in SIF Ghana portfolio.', 'body' => 'SIF programmes include GWYESCO, PSDPEP, IRDP II and other development interventions supporting communities, women, youth and institutions.', 'seo_title' => 'Projects | SIF Ghana', 'seo_description' => 'Explore SIF Ghana programmes and project portfolio.'],
            ['title' => 'News and Media', 'slug' => 'news', 'template' => 'news', 'excerpt' => 'Programme updates, partnership news and announcements from SIF.', 'body' => 'Read programme updates, partnership news, environmental and social notices, and media announcements from SIF Ghana.', 'seo_title' => 'News and Media | SIF Ghana', 'seo_description' => 'Read the latest news, press releases and media updates from SIF Ghana.'],
            ['title' => 'Resources', 'slug' => 'resources', 'template' => 'resources', 'excerpt' => 'Reports, policies, procurement notices and public documents.', 'body' => 'The SIF resource centre provides corporate reports, publications, procurement notices, safeguard documents and institutional policies.', 'seo_title' => 'Resources, Reports and Publications | SIF Ghana', 'seo_description' => 'Download SIF Ghana reports, publications, procurement notices and safeguards documents.'],
            ['title' => 'Contact', 'slug' => 'contact', 'template' => 'contact', 'excerpt' => 'Contact the Social Investment Fund Ghana.', 'body' => 'Reach SIF Ghana for general enquiries, media requests, procurement questions and stakeholder support.', 'seo_title' => 'Contact | SIF Ghana', 'seo_description' => 'Contact the Social Investment Fund Ghana.'],
            ['title' => 'Complaint', 'slug' => 'complaint', 'template' => 'complaint', 'excerpt' => 'Submit a complaint or grievance to SIF Ghana.', 'body' => 'SIF Ghana provides channels for stakeholders and communities to submit complaints, grievances and feedback.', 'seo_title' => 'Complaint | SIF Ghana', 'seo_description' => 'Submit a complaint or grievance to SIF Ghana.'],
            ['title' => 'Privacy Policy', 'slug' => 'privacy', 'template' => 'legal', 'excerpt' => 'SIF Ghana privacy policy.', 'body' => 'This page explains how SIF Ghana handles website privacy, public enquiries and personal information submitted through the website.', 'seo_title' => 'Privacy Policy | SIF Ghana', 'seo_description' => 'Read the SIF Ghana privacy policy.'],
            ['title' => 'Terms of Use', 'slug' => 'terms', 'template' => 'legal', 'excerpt' => 'Website terms of use.', 'body' => 'These terms govern use of the SIF Ghana website and public information published through the site.', 'seo_title' => 'Terms of Use | SIF Ghana', 'seo_description' => 'Read SIF Ghana website terms of use.'],
            ['title' => 'Accessibility', 'slug' => 'accessibility', 'template' => 'accessibility', 'excerpt' => 'SIF Ghana website accessibility statement.', 'body' => 'SIF Ghana aims to make public information accessible, readable and useful to all website visitors.', 'seo_title' => 'Accessibility | SIF Ghana', 'seo_description' => 'Read the SIF Ghana accessibility statement.'],
            ['title' => 'Sitemap', 'slug' => 'sitemap', 'template' => 'sitemap', 'excerpt' => 'Find public pages on the SIF Ghana website.', 'body' => 'The sitemap lists major public sections of the SIF Ghana website.', 'seo_title' => 'Sitemap | SIF Ghana', 'seo_description' => 'Find public pages on the SIF Ghana website.'],
        ];

        foreach ($pages as $page) {
            CmsPage::query()->updateOrCreate(
                ['slug' => $page['slug']],
                $page + [
                    'status' => 'published',
                    'published_at' => $now,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );
        }
    }

    private function seedPosts(array $categoryIds, ?int $adminId, $now): void
    {
        $posts = [
            [
                'title' => 'Ghana launches GWYESCO to create 30,000+ jobs for women and youth',
                'slug' => 'ghana-launches-gwyesco-to-create-30000-jobs-for-women-and-youth',
                'category_slug' => 'programme-updates',
                'category' => 'Programme Updates',
                'excerpt' => 'A US$71.25M AfDB grant backs Ghana first results-based financing programme, implemented by SIF in partnership with the Ministry of Finance.',
                'body' => 'Ghana launched the Green Jobs for Women and Youth through the Social Cohesion programme, known as GWYESCO. The programme is backed by an AfDB grant and implemented by SIF in partnership with the Ministry of Finance. Source: https://www.myjoyonline.com/ghana-launches-landmark-women-and-youth-employment-programme-to-create-over-30000-jobs/',
                'featured_image' => 'images/gwyesco-launch.jpg',
                'published_at' => '2026-06-15 09:00:00',
            ],
            [
                'title' => 'BADEA appraisal mission meets with SIF and Ministry of Finance',
                'slug' => 'badea-appraisal-mission-meets-with-sif-and-ministry-of-finance',
                'category_slug' => 'partnerships',
                'category' => 'Partnerships',
                'excerpt' => 'A BADEA delegation visited SIF and the Ministry of Finance as part of an ongoing appraisal mission.',
                'body' => 'A BADEA delegation visited SIF and the Ministry of Finance as part of an appraisal mission focused on continued development cooperation and programme preparation. Source: https://sifinghana.org/page.php?id=3278',
                'featured_image' => 'https://sifinghana.org/backend/images/uploads/20260119_133435-2.jpg.jpeg1769166677.jpeg',
                'published_at' => '2026-01-19 09:00:00',
            ],
            [
                'title' => 'SIF hands over UG Biotechnology Centre site to contractors',
                'slug' => 'sif-hands-over-ug-biotechnology-centre-site-to-contractors',
                'category_slug' => 'community',
                'category' => 'Community',
                'excerpt' => 'Site handover marks the start of construction works at the University of Ghana Biotechnology Centre.',
                'body' => 'SIF handed over the University of Ghana Biotechnology Centre site to contractors, marking the start of construction works and field activity. Source: https://sifinghana.org/page.php?id=3255',
                'featured_image' => 'https://sifinghana.org/backend/images/uploads/WhatsApp%20Image%202026-01-21%20at%204.15.48%20PM.jpeg1769013143.jpeg',
                'published_at' => '2026-01-21 09:00:00',
            ],
            [
                'title' => 'Environmental and Social Management Plan published',
                'slug' => 'environmental-and-social-management-plan-published',
                'category_slug' => 'environmental-social',
                'category' => 'Environmental & Social',
                'excerpt' => 'SIF publishes the ESMP covering safeguard arrangements for its newest programme.',
                'body' => 'SIF published the Environmental and Social Management Plan covering safeguards arrangements for programme implementation. Source: https://sifinghana.org/page.php?id=3253',
                'featured_image' => 'https://sifinghana.org/backend/images/uploads/1751046892_8d6c9a3176e1e094_SIFNEWPROJECTESMP.jpg',
                'published_at' => '2025-06-27 09:00:00',
            ],
        ];

        foreach ($posts as $post) {
            $categorySlug = $post['category_slug'];
            unset($post['category_slug']);

            CmsPost::query()->updateOrCreate(
                ['slug' => $post['slug']],
                $post + [
                    'type' => 'news',
                    'category_id' => $categoryIds[$categorySlug] ?? null,
                    'status' => 'published',
                    'seo_title' => $post['title'].' | SIF Ghana',
                    'seo_description' => $post['excerpt'],
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );
        }
    }

    private function seedPressReleases(?int $adminId, $now): void
    {
        $items = [
            ['title' => 'Ghana launches landmark women and youth employment programme', 'slug' => 'ghana-launches-landmark-women-and-youth-employment-programme', 'brief_description' => 'GWYESCO is designed to create more than 30,000 jobs for women and youth.', 'body' => 'The Green Jobs for Women and Youth through the Social Cohesion programme is a major employment and resilience intervention implemented by SIF with government and development partner support.'],
            ['title' => 'BADEA appraisal mission engages SIF and Ministry of Finance', 'slug' => 'badea-appraisal-mission-engages-sif-and-ministry-of-finance', 'brief_description' => 'BADEA representatives met SIF and Ministry of Finance officials during an appraisal mission.', 'body' => 'The appraisal mission formed part of ongoing institutional and programme engagement with SIF Ghana.'],
        ];

        foreach ($items as $item) {
            PressRelease::query()->updateOrCreate(
                ['slug' => $item['slug']],
                $item + [
                    'status' => 'published',
                    'published_at' => $now,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );
        }
    }

    private function seedNotices(?int $adminId, $now): void
    {
        $items = [
            ['title' => 'Current tenders and contractor opportunities', 'slug' => 'current-tenders-and-contractor-opportunities', 'brief_description' => 'Procurement notices and contractor opportunities are updated periodically.', 'body' => 'Interested vendors and contractors should monitor the resource centre and contact SIF procurement for current opportunities.'],
            ['title' => 'Public consultations and safeguard engagement schedules', 'slug' => 'public-consultations-and-safeguard-engagement-schedules', 'brief_description' => 'Community engagement schedules and safeguard consultation notices.', 'body' => 'SIF publishes public consultation and environmental and social safeguard notices for relevant programme activities.'],
            ['title' => 'Vacancies and institutional opportunities', 'slug' => 'vacancies-and-institutional-opportunities', 'brief_description' => 'Current opportunities at SIF Ghana.', 'body' => 'SIF Ghana publishes vacancies and institutional opportunities through official channels when available.'],
        ];

        foreach ($items as $item) {
            Notice::query()->updateOrCreate(
                ['slug' => $item['slug']],
                $item + [
                    'status' => 'published',
                    'published_at' => $now,
                    'expires_at' => null,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );
        }
    }

    private function seedDocuments(?int $adminId, $now): void
    {
        $items = [
            ['type' => 'publications', 'title' => 'Corporate Profile', 'slug' => 'corporate-profile', 'brief_description' => 'SIF institutional corporate profile.', 'fiscal_year' => '2026', 'file_path' => 'https://sifinghana.org/images/DRAFT%20Social%20Investment%20Fund%20Corporate%20Profile.pdf', 'file_name' => 'SIF Corporate Profile.pdf', 'file_mime_type' => 'application/pdf', 'sort_order' => 10],
            ['type' => 'publications', 'title' => 'Publications Archive', 'slug' => 'publications-archive', 'brief_description' => 'SIF Ghana publications archive.', 'fiscal_year' => '2026', 'file_path' => 'https://sifinghana.org/publications.php', 'file_name' => 'Publications Archive', 'file_mime_type' => 'text/html', 'sort_order' => 20],
            ['type' => 'annual-reports', 'title' => 'SIF Annual Reports Archive', 'slug' => 'sif-annual-reports-archive', 'brief_description' => 'Financial and performance reports archive.', 'fiscal_year' => '2026', 'file_path' => 'https://sifinghana.org/annual-reports.php', 'file_name' => 'Annual Reports Archive', 'file_mime_type' => 'text/html', 'sort_order' => 30],
            ['type' => 'procurement-notices', 'title' => 'Current Tenders and Contractor Opportunities', 'slug' => 'current-tenders-and-contractor-opportunities-document', 'brief_description' => 'Open tenders and contractor opportunities.', 'fiscal_year' => '2026', 'file_path' => '/contact', 'file_name' => 'Contact Procurement', 'file_mime_type' => 'text/html', 'sort_order' => 40],
            ['type' => 'environmental-social-documents', 'title' => 'Environmental and Social Management Plan', 'slug' => 'environmental-and-social-management-plan', 'brief_description' => 'Environmental and social safeguards document for programme implementation.', 'fiscal_year' => '2025', 'file_path' => 'https://sifinghana.org/page.php?id=3253', 'file_name' => 'Environmental and Social Management Plan', 'file_mime_type' => 'text/html', 'sort_order' => 50],
            ['type' => 'policies-downloads', 'title' => 'Institutional Policies', 'slug' => 'institutional-policies', 'brief_description' => 'SIF Ghana policies and public downloads.', 'fiscal_year' => '2026', 'file_path' => 'https://sifinghana.org/', 'file_name' => 'Institutional Policies', 'file_mime_type' => 'text/html', 'sort_order' => 60],
        ];

        foreach ($items as $item) {
            Document::query()->updateOrCreate(
                ['slug' => $item['slug']],
                $item + [
                    'status' => 'published',
                    'published_at' => $now,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );
        }
    }

    private function seedPeople(?int $adminId, $now): void
    {
        $people = [
            ['group' => 'management', 'name' => 'Abass Nurudeen, ESQ', 'position' => 'Chief Executive Officer', 'brief_profile' => 'Coordinates strategy, delivery and partner confidence across SIF Ghana.', 'photo_path' => 'https://sifinghana.org/backend/images/uploads/IMG-20250124-WA0101.jpg1738238916.jpg'],
            ['group' => 'management', 'name' => 'Prosper Puo-Ire', 'position' => 'Deputy CEO', 'brief_profile' => 'Supports executive coordination, development administration and institutional delivery.', 'photo_path' => 'https://sifinghana.org/backend/images/uploads/for_website_2.jpg1742986137-removebg-preview.png1743151474.png'],
            ['group' => 'management', 'name' => 'Paa Yaw Arkoh-Koomson', 'position' => 'Director for Finance and Accounting', 'brief_profile' => 'Leads financial management, accounting and compliance across SIF programmes.', 'photo_path' => 'https://sifinghana.org/backend/images/uploads/Paa%20Yaw%202.jpg1757338869.jpg'],
            ['group' => 'management', 'name' => 'Kwaku Agbesi, PhD', 'position' => 'Procurement Specialist', 'brief_profile' => 'Oversees procurement processes for transparent sourcing of goods, works and services.', 'photo_path' => 'https://sifinghana.org/backend/images/uploads/Agbesi%20pic.JPG1742914873.JPG'],
            ['group' => 'management', 'name' => 'MacDonald Acquah', 'position' => 'Monitoring & Evaluation Specialist', 'brief_profile' => 'Tracks implementation progress, results and impact across SIF interventions.', 'photo_path' => 'https://sifinghana.org/backend/images/uploads/macD.jpg1742389371.jpg'],
            ['group' => 'management', 'name' => 'Heinz Osei Karikari', 'position' => 'Zonal Coordinator - Zone 2', 'brief_profile' => 'Coordinates zonal delivery, local partnerships and field-level implementation support.', 'photo_path' => 'https://sifinghana.org/backend/images/uploads/heinz%20111.jpg1741883521.jpg'],
            ['group' => 'management', 'name' => 'Moses Kwame Ohene', 'position' => 'Zonal Coordinator - Zone 3', 'brief_profile' => 'Supports project coordination, community engagement and zonal operations.', 'photo_path' => 'https://sifinghana.org/backend/images/uploads/Togbe%202.jpg1742388941.jpg'],
            ['group' => 'management', 'name' => 'Joseph Ofosu-Kwarteng', 'position' => 'Zonal Coordinator - Zone 4', 'brief_profile' => 'Coordinates field operations and stakeholder support across assigned programme areas.', 'photo_path' => 'https://sifinghana.org/backend/images/uploads/OFOSU%201.JPG1741867679.JPG'],
            ['group' => 'management', 'name' => 'Stella Arthur', 'position' => 'Administrative Officer', 'brief_profile' => 'Provides administrative coordination and institutional support for SIF operations.', 'photo_path' => 'https://sifinghana.org/backend/images/uploads/IMG-20240924-WA0009.jpg1727182387.jpg'],
            ['group' => 'board', 'name' => 'Board Chairperson', 'position' => 'Chairperson', 'brief_profile' => 'Provides governance leadership, strategic oversight and public accountability for SIF Ghana.', 'photo_path' => null],
        ];

        foreach ($people as $index => $person) {
            Person::query()->updateOrCreate(
                ['slug' => Str::slug($person['name'])],
                $person + [
                    'slug' => Str::slug($person['name']),
                    'status' => 'published',
                    'show_seal' => true,
                    'sort_order' => ($index + 1) * 10,
                    'published_at' => $now,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );
        }
    }

    private function seedGallery(?int $adminId, $now): void
    {
        $albums = [
            [
                'title' => 'Project Sites and Handovers',
                'slug' => 'project-sites-and-handovers',
                'description' => 'Photography from project sites, handovers and field missions across SIF operational zones.',
                'cover_image_path' => 'images/11-5-scaled.jpg',
                'images' => [
                    ['image_path' => 'images/gwyesco-launch.jpg', 'alt_text' => 'GWYESCO launch', 'caption' => 'GWYESCO programme launch.'],
                    ['image_path' => 'https://sifinghana.org/backend/images/uploads/WhatsApp%20Image%202026-01-21%20at%204.15.48%20PM.jpeg1769013143.jpeg', 'alt_text' => 'Site handover', 'caption' => 'University of Ghana Biotechnology Centre site handover.'],
                    ['image_path' => 'https://sifinghana.org/backend/images/uploads/20260119_133435-2.jpg.jpeg1769166677.jpeg', 'alt_text' => 'BADEA appraisal mission', 'caption' => 'BADEA appraisal mission engagement.'],
                ],
            ],
        ];

        foreach ($albums as $albumData) {
            $images = $albumData['images'];
            unset($albumData['images']);

            $album = GalleryAlbum::query()->updateOrCreate(
                ['slug' => $albumData['slug']],
                $albumData + [
                    'status' => 'published',
                    'sort_order' => 10,
                    'published_at' => $now,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );

            foreach ($images as $index => $image) {
                GalleryImage::query()->updateOrCreate(
                    ['gallery_album_id' => $album->id, 'image_path' => $image['image_path']],
                    $image + ['sort_order' => ($index + 1) * 10]
                );
            }
        }
    }

    private function seedGraphics(?int $adminId, $now): void
    {
        $items = [
            ['title' => 'GWYESCO Programme Launch', 'slug' => 'gwyesco-programme-launch', 'description' => 'Visual from the GWYESCO launch and programme communications.', 'image_path' => 'images/gwyesco-launch.jpg', 'alt_text' => 'GWYESCO programme launch'],
            ['title' => 'Environmental and Social Management Plan', 'slug' => 'environmental-social-management-plan-graphic', 'description' => 'Safeguard communication graphic for SIF environmental and social documentation.', 'image_path' => 'https://sifinghana.org/backend/images/uploads/1751046892_8d6c9a3176e1e094_SIFNEWPROJECTESMP.jpg', 'alt_text' => 'Environmental and Social Management Plan'],
        ];

        foreach ($items as $index => $item) {
            Graphic::query()->updateOrCreate(
                ['slug' => $item['slug']],
                $item + [
                    'status' => 'published',
                    'sort_order' => ($index + 1) * 10,
                    'published_at' => $now,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );
        }
    }

    private function seedVideos(?int $adminId, $now): void
    {
        $items = [
            ['title' => 'SIF Ghana Programme Overview', 'slug' => 'sif-ghana-programme-overview', 'description' => 'Overview video placeholder for SIF programme communications and media archive.', 'video_url' => 'https://sifinghana.org/gallery.php', 'embed_url' => 'https://sifinghana.org/gallery.php', 'thumbnail_path' => 'images/New-Project-1.jpg'],
        ];

        foreach ($items as $index => $item) {
            Video::query()->updateOrCreate(
                ['slug' => $item['slug']],
                $item + [
                    'status' => 'published',
                    'sort_order' => ($index + 1) * 10,
                    'published_at' => $now,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );
        }
    }
}
