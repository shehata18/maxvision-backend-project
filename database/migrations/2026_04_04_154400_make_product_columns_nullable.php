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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('pixel_pitch', 4, 2)->nullable()->change();
            $table->integer('brightness_min')->nullable()->change();
            $table->integer('brightness_max')->nullable()->change();
            $table->string('cabinet_size', 100)->nullable()->change();
            $table->string('weight', 50)->nullable()->change();
            $table->string('power_consumption', 100)->nullable()->change();
            $table->string('protection_rating', 50)->nullable()->change();
            $table->string('lifespan', 50)->nullable()->change();
            $table->string('operating_temp', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('pixel_pitch', 4, 2)->nullable(false)->change();
            $table->integer('brightness_min')->nullable(false)->change();
            $table->integer('brightness_max')->nullable(false)->change();
            $table->string('cabinet_size', 100)->nullable(false)->change();
            $table->string('weight', 50)->nullable(false)->change();
            $table->string('power_consumption', 100)->nullable(false)->change();
            $table->string('protection_rating', 50)->nullable(false)->change();
            $table->string('lifespan', 50)->nullable(false)->change();
            $table->string('operating_temp', 50)->nullable(false)->change();
        });
    }
};
