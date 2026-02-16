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
        Schema::create('case_study_specs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_study_id');
            $table->string('label', 100);
            $table->string('value', 255);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('case_study_id')
                ->references('id')
                ->on('case_studies')
                ->onDelete('cascade');

            $table->index(['case_study_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_study_specs');
    }
};
