<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('full_name');
            $table->string('status')->default('draft')->index();
            $table->string('project_status')->default('ongoing')->index();
            $table->string('status_label')->nullable();
            $table->string('timeline')->nullable();
            $table->string('funder')->nullable();
            $table->string('fund_amount')->nullable();
            $table->string('zone_key', 12)->nullable();
            $table->string('zone_name')->nullable();
            $table->string('image')->nullable();
            $table->text('summary')->nullable();
            $table->text('beneficiaries')->nullable();
            $table->json('categories')->nullable();
            $table->json('regions')->nullable();
            $table->json('objectives')->nullable();
            $table->json('outcomes')->nullable();
            $table->json('documents')->nullable();
            $table->json('related_projects')->nullable();
            $table->json('markers')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
