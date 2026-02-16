<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add composite and single-column indexes for API query performance.
     * Note: category, is_active, and name already have individual indexes
     * from the original products migration.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Composite index for filtered queries (category + active status)
            $table->index(['category', 'is_active'], 'products_category_active_index');

            // Single-column indexes for range filtering (not in original migration)
            $table->index('pixel_pitch', 'products_pixel_pitch_index');
            $table->index('brightness_min', 'products_brightness_min_index');
            $table->index('brightness_max', 'products_brightness_max_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_category_active_index');
            $table->dropIndex('products_pixel_pitch_index');
            $table->dropIndex('products_brightness_min_index');
            $table->dropIndex('products_brightness_max_index');
        });
    }
};
