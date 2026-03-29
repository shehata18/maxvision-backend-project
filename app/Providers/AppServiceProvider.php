<?php

namespace App\Providers;

use App\Models\CaseStudy;
use App\Models\CompanyInfo;
use App\Models\ConsultationBooking;
use App\Models\ContactSubmission;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Solution;
use App\Observers\CaseStudyObserver;
use App\Observers\CompanyInfoObserver;
use App\Observers\ConsultationBookingObserver;
use App\Observers\ContactSubmissionObserver;
use App\Observers\ProductObserver;
use App\Observers\SettingObserver;
use App\Observers\SolutionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\AnalyticsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ContactSubmission::observe(ContactSubmissionObserver::class);
        ConsultationBooking::observe(ConsultationBookingObserver::class);
        Product::observe(ProductObserver::class);
        Solution::observe(SolutionObserver::class);
        CaseStudy::observe(CaseStudyObserver::class);
        Setting::observe(SettingObserver::class);
        CompanyInfo::observe(CompanyInfoObserver::class);
    }
}
