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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->index();
            $table->string('series', 100);
            $table->enum('category', ['outdoor', 'indoor', 'transparent', 'posters'])->index();
            $table->decimal('pixel_pitch', 4, 2);
            $table->integer('brightness_min');
            $table->integer('brightness_max');
            $table->string('cabinet_size', 100);
            $table->string('weight', 50);
            $table->string('power_consumption', 100);
            $table->string('protection_rating', 50);
            $table->string('lifespan', 50);
            $table->string('operating_temp', 50);
            $table->string('environment', 50);
            $table->string('price', 100)->nullable();
            $table->string('tagline', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->json('gallery')->nullable();
            $table->string('slug', 255)->unique();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
