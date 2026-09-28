<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table): void {
                $table->index(['status', 'type', 'published_at'], 'posts_public_listing_index');
            });
        }

        if (Schema::hasTable('press_releases')) {
            Schema::table('press_releases', function (Blueprint $table): void {
                $table->index(['status', 'published_at'], 'press_releases_public_listing_index');
            });
        }

        if (Schema::hasTable('notices')) {
            Schema::table('notices', function (Blueprint $table): void {
                $table->index(['status', 'published_at', 'expires_at'], 'notices_public_listing_index');
            });
        }

        if (Schema::hasTable('documents')) {
            Schema::table('documents', function (Blueprint $table): void {
                $table->index(['type', 'status', 'sort_order', 'document_date'], 'documents_public_listing_index');
            });
        }

        if (Schema::hasTable('people')) {
            Schema::table('people', function (Blueprint $table): void {
                $table->index(['group', 'status', 'sort_order'], 'people_public_listing_index');
            });
        }

        if (Schema::hasTable('purchase_prices')) {
            Schema::table('purchase_prices', function (Blueprint $table): void {
                $table->index(['status', 'show_on_home', 'display_at'], 'purchase_prices_home_index');
            });
        }

        if (Schema::hasTable('license_registry_entries')) {
            Schema::table('license_registry_entries', function (Blueprint $table): void {
                $table->index(['status', 'category', 'registry_number'], 'license_registry_public_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $indexes = [
            'posts' => 'posts_public_listing_index',
            'press_releases' => 'press_releases_public_listing_index',
            'notices' => 'notices_public_listing_index',
            'documents' => 'documents_public_listing_index',
            'people' => 'people_public_listing_index',
            'purchase_prices' => 'purchase_prices_home_index',
            'license_registry_entries' => 'license_registry_public_index',
        ];

        foreach ($indexes as $tableName => $indexName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($indexName): void {
                    $table->dropIndex($indexName);
                });
            }
        }
    }
};
