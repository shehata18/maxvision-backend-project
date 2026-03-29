<?php

use App\Http\Controllers\Api\CaseStudyController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\Api\JobListingController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SolutionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ─── Products ────────────────────────────────────────────────────
Route::get('/products/categories', [ProductController::class, 'categories']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

// ─── Solutions ───────────────────────────────────────────────────
Route::get('/solutions', [SolutionController::class, 'index']);
Route::get('/solutions/{slug}', [SolutionController::class, 'show']);

// ─── Case Studies ────────────────────────────────────────────────
Route::get('/case-studies', [CaseStudyController::class, 'index']);
Route::get('/case-studies/{slug}', [CaseStudyController::class, 'show']);

// ─── Company ─────────────────────────────────────────────────────
Route::get('/company/about', [CompanyController::class, 'about']);
Route::get('/company/settings', [CompanyController::class, 'settings']);

// ─── Contact / Quote Request ─────────────────────────────────────
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:contact');

// ─── Consultation Bookings ────────────────────────────────────────
Route::post('/consultation-bookings', [\App\Http\Controllers\Api\ConsultationBookingController::class, 'store'])->middleware('throttle:contact');
Route::get('/consultation-bookings/time-slots', [\App\Http\Controllers\Api\ConsultationBookingController::class, 'availableSlots']);

// ─── Careers / Job Listings ───────────────────────────────────────
Route::get('/jobs/filters', [JobListingController::class, 'filters']);
Route::get('/jobs', [JobListingController::class, 'index']);
Route::get('/jobs/{slug}', [JobListingController::class, 'show']);

// ─── Job Applications ──────────────────────────────────────────────
Route::post('/job-applications', [JobApplicationController::class, 'store'])->middleware('throttle:contact');

