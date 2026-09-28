<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('purchase_prices')) {
            return;
        }

        Schema::table('purchase_prices', function (Blueprint $table): void {
            if (! Schema::hasColumn('purchase_prices', 'lbma_price_session')) {
                $table->string('lbma_price_session', 2)->default('PM');
            }

            if (! Schema::hasColumn('purchase_prices', 'lbma_price_visibility')) {
                $table->string('lbma_price_visibility', 20)->default('visible');
            }

            if (! Schema::hasColumn('purchase_prices', 'rate_label')) {
                $table->string('rate_label')->default('Exchange Rate');
            }

            if (! Schema::hasColumn('purchase_prices', 'rate_visibility')) {
                $table->string('rate_visibility', 20)->default('visible');
            }

            if (! Schema::hasColumn('purchase_prices', 'secondary_rate_label')) {
                $table->string('secondary_rate_label')->nullable();
            }

            if (! Schema::hasColumn('purchase_prices', 'secondary_rate')) {
                $table->decimal('secondary_rate', 12, 4)->nullable();
            }

            if (! Schema::hasColumn('purchase_prices', 'secondary_rate_visibility')) {
                $table->string('secondary_rate_visibility', 20)->default('hidden');
            }

            if (! Schema::hasColumn('purchase_prices', 'discount_rate_visibility')) {
                $table->string('discount_rate_visibility', 20)->default('visible');
            }

            if (! Schema::hasColumn('purchase_prices', 'total_price_visibility')) {
                $table->string('total_price_visibility', 20)->default('visible');
            }

            if (! Schema::hasColumn('purchase_prices', 'bonus_label')) {
                $table->string('bonus_label')->nullable();
            }

            if (! Schema::hasColumn('purchase_prices', 'bonus_amount')) {
                $table->decimal('bonus_amount', 12, 2)->nullable();
            }

            if (! Schema::hasColumn('purchase_prices', 'bonus_visibility')) {
                $table->string('bonus_visibility', 20)->default('hidden');
            }

            if (! Schema::hasColumn('purchase_prices', 'alternate_total_label')) {
                $table->string('alternate_total_label')->nullable();
            }

            if (! Schema::hasColumn('purchase_prices', 'alternate_total_amount')) {
                $table->decimal('alternate_total_amount', 12, 2)->nullable();
            }

            if (! Schema::hasColumn('purchase_prices', 'alternate_total_visibility')) {
                $table->string('alternate_total_visibility', 20)->default('hidden');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('purchase_prices')) {
            return;
        }

        Schema::table('purchase_prices', function (Blueprint $table): void {
            foreach ([
                'lbma_price_session',
                'lbma_price_visibility',
                'rate_label',
                'rate_visibility',
                'secondary_rate_label',
                'secondary_rate',
                'secondary_rate_visibility',
                'discount_rate_visibility',
                'total_price_visibility',
                'bonus_label',
                'bonus_amount',
                'bonus_visibility',
                'alternate_total_label',
                'alternate_total_amount',
                'alternate_total_visibility',
            ] as $column) {
                if (Schema::hasColumn('purchase_prices', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
