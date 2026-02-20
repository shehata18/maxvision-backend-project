<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin routes for job applications
Route::get('/admin/job-applications/{id}/download-resume', [\App\Http\Controllers\Api\JobApplicationController::class, 'downloadResume'])
    ->middleware(['auth'])
    ->name('admin.job-applications.download-resume');
