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
        if (! Schema::hasTable('license_registry_entries')) {
            Schema::create('license_registry_entries', function (Blueprint $table) {
                $table->id();
                $table->string('category')->index();
                $table->unsignedInteger('registry_number')->nullable();
                $table->string('business_name');
                $table->string('certificate_number');
                $table->date('issued_date')->nullable()->index();
                $table->date('expiry_date')->nullable()->index();
                $table->string('status')->default('active')->index();
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->unique(['category', 'certificate_number']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_registry_entries');
    }
};
