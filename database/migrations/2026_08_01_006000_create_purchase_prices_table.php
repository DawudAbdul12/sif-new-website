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
        if (! Schema::hasTable('purchase_prices')) {
            Schema::create('purchase_prices', function (Blueprint $table) {
                $table->id();
                $table->string('title')->default('GoldBod Approved Purchase Price per Pound');
                $table->string('subtitle')->nullable();
                $table->string('status')->default('draft')->index();
                $table->decimal('lbma_pm_price', 12, 2);
                $table->decimal('exchange_rate', 12, 4);
                $table->decimal('discount_rate', 6, 2)->default(0);
                $table->decimal('total_price_per_pound', 12, 2);
                $table->string('price_currency', 10)->default('USD');
                $table->string('currency', 10)->default('GHS');
                $table->timestamp('display_at')->nullable()->index();
                $table->timestamp('valid_from')->nullable();
                $table->timestamp('valid_until')->nullable();
                $table->boolean('show_on_home')->default(false)->index();
                $table->unsignedInteger('sort_order')->default(0);
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_prices');
    }
};
