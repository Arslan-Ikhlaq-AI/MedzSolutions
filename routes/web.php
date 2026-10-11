<?php

use App\Http\Controllers\AffiliateApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\ConsultationRequestController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/meet-our-team', [PageController::class, 'team'])->name('team');
Route::get('/specialities', [PageController::class, 'specialities'])->name('specialities');
Route::get('/software', [PageController::class, 'software'])->name('software');
Route::get('/compliance', [PageController::class, 'compliance'])->name('compliance');
Route::get('/faqs', [PageController::class, 'faqs'])->name('faqs');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
Route::get('/resources/{slug}', [ResourceController::class, 'show'])->name('resources.show');

Route::get('/blogs', [PostController::class, 'index'])->name('posts.index');
Route::get('/blogs/{slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{slug}', [CareerController::class, 'show'])->name('careers.show');
Route::post('/careers/{slug}/apply', [JobApplicationController::class, 'store'])->middleware('throttle:5,1')->name('careers.apply');

Route::get('/affiliate-program', [AffiliateApplicationController::class, 'create'])->name('affiliate');
Route::post('/affiliate-program', [AffiliateApplicationController::class, 'store'])->middleware('throttle:5,1')->name('affiliate.store');

Route::post('/consultations', [ConsultationRequestController::class, 'store'])->middleware('throttle:5,1')->name('consultations.store');

Route::get('/contact-us', [ContactMessageController::class, 'create'])->name('contact');
Route::post('/contact-us', [ContactMessageController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('welcome', ['mode' => 'login']))->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', fn () => view('welcome', ['mode' => 'register']))->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('welcome', ['mode' => 'dashboard']))->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
