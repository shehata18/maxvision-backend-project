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
        Schema::create('solution_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solution_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('display_name', 255);
            $table->string('series', 100);
            $table->string('pitch', 50);
            $table->string('brightness', 50);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('solution_id')
                ->references('id')
                ->on('solutions')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->index(['solution_id', 'order']);
            $table->index('product_id');

            // Unique constraint on solution_id + product_id where product_id is not null
            // MySQL doesn't enforce unique on nullable columns the same way,
            // so this effectively prevents duplicate non-null product_id per solution
            $table->unique(['solution_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solution_product');
    }
};
