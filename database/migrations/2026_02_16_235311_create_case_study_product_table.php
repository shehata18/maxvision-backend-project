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
        Schema::create('case_study_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_study_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name', 255);
            $table->timestamps();

            $table->foreign('case_study_id')
                ->references('id')
                ->on('case_studies')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->index('case_study_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_study_product');
    }
};
