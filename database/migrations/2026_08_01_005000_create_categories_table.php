<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('all')->index();
            $table->string('status')->default('active')->index();
            $table->text('description')->nullable();
            $table->string('color', 20)->default('#17472d');
            $table->unsignedInteger('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('type')
                ->constrained('categories')
                ->nullOnDelete();
        });

        if (Schema::hasColumn('posts', 'category')) {
            $now = now();
            $categories = DB::table('posts')
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct()
                ->pluck('category')
                ->filter()
                ->map(fn (string $category): string => trim($category))
                ->unique(fn (string $category): string => str($category)->lower()->toString());

            foreach ($categories as $category) {
                $slug = Str::slug($category) ?: 'category';
                $baseSlug = $slug;
                $counter = 2;

                while (DB::table('categories')->where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$counter;
                    $counter++;
                }

                $categoryId = DB::table('categories')->insertGetId([
                    'name' => $category,
                    'slug' => $slug,
                    'type' => 'all',
                    'status' => 'active',
                    'description' => null,
                    'color' => '#17472d',
                    'sort_order' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                DB::table('posts')->where('category', $category)->update(['category_id' => $categoryId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::dropIfExists('categories');
    }
};
