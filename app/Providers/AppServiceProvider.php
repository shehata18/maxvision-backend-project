<?php

namespace App\Providers;

use App\Models\ContactSubmission;
use App\Models\Product;
use App\Observers\ContactSubmissionObserver;
use App\Observers\ProductObserver;
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
        Product::observe(ProductObserver::class);
    }
}
