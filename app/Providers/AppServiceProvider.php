<?php

namespace App\Providers;

use App\Models\ContactSubmission;
use App\Observers\ContactSubmissionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ContactSubmission::observe(ContactSubmissionObserver::class);
    }
}
