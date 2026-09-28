<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_albums', function (Blueprint $table) {
            $table->foreignId('cover_media_asset_id')->nullable()->after('cover_image_path')->constrained('media_assets')->nullOnDelete();
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->foreignId('media_asset_id')->nullable()->after('gallery_album_id')->constrained('media_assets')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropConstrainedForeignId('media_asset_id');
        });

        Schema::table('gallery_albums', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cover_media_asset_id');
        });
    }
};
