<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('cms_pages') && ! Schema::hasTable('pages')) {
            Schema::rename('cms_pages', 'pages');
        }

        if (Schema::hasTable('cms_posts') && ! Schema::hasTable('posts')) {
            Schema::rename('cms_posts', 'posts');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pages') && ! Schema::hasTable('cms_pages')) {
            Schema::rename('pages', 'cms_pages');
        }

        if (Schema::hasTable('posts') && ! Schema::hasTable('cms_posts')) {
            Schema::rename('posts', 'cms_posts');
        }
    }
};
