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
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique();
            $table->string('title', 255);
            $table->string('department', 100)->index();
            $table->string('location', 100)->index();
            $table->string('job_type', 50)->index(); // full-time, part-time, contract, internship
            $table->string('category', 100)->index(); // engineering, sales, marketing, etc.
            $table->text('summary');
            $table->text('description')->nullable();
            $table->json('requirements')->nullable(); // Array of requirements
            $table->json('benefits')->nullable(); // Array of job benefits
            $table->string('salary_range', 100)->nullable();
            $table->date('posted_at')->nullable();
            $table->date('deadline')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            // Indexes for common queries
            $table->index(['is_active', 'posted_at']);
            $table->index(['department', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
