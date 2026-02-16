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
        Schema::create('solution_specs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solution_id');
            $table->string('label', 100);
            $table->string('value', 255);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('solution_id')
                ->references('id')
                ->on('solutions')
                ->onDelete('cascade');

            $table->index(['solution_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solution_specs');
    }
};
