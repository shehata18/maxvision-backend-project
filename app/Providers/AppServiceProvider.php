<?php

namespace App\Providers;

use App\Models\CaseStudy;
use App\Models\ContactSubmission;
use App\Models\Product;
use App\Models\Solution;
use App\Observers\CaseStudyObserver;
use App\Observers\ContactSubmissionObserver;
use App\Observers\ProductObserver;
use App\Observers\SolutionObserver;
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
        Solution::observe(SolutionObserver::class);
        CaseStudy::observe(CaseStudyObserver::class);
    }
}
