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
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 255)->index();
            $table->string('phone', 30)->nullable();
            $table->string('company', 255)->nullable();
            $table->string('project_type', 100)->index();
            $table->string('timeline', 100)->index();
            $table->string('size_requirements', 500);
            $table->string('budget_range', 100)->index();
            $table->text('message')->nullable();
            $table->enum('status', ['new', 'contacted', 'converted'])->default('new')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};
