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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_listing_id')->nullable()->constrained()->nullOnDelete();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 255)->index();
            $table->string('phone', 50)->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('resume_path', 500)->nullable();
            $table->string('resume_original_name', 255)->nullable();
            $table->string('linkedin_url', 500)->nullable();
            $table->string('portfolio_url', 500)->nullable();
            $table->string('status', 50)->default('pending')->index();
            $table->text('notes')->nullable(); // Admin notes
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_general_application')->default(false); // True if not applying for specific job
            $table->timestamps();

            // Indexes for common queries
            $table->index(['status', 'created_at']);
            $table->index(['job_listing_id', 'status']);
            $table->index('is_general_application');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
