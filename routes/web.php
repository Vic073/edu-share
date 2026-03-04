<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyMaterialsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\InstitutionController;
use Illuminate\Support\Facades\Auth;



// Redirect root URL to login page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Auth::routes();

// Institution API Routes (no auth required — used in registration)
Route::get('/api/institutions', [InstitutionController::class, 'index'])->name('api.institutions');
Route::get('/api/institutions/{institution}/faculties', [InstitutionController::class, 'faculties'])->name('api.faculties');
Route::get('/api/faculties/{faculty}/courses', [InstitutionController::class, 'courses'])->name('api.courses');

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Terms & Conditions
Route::get('terms', function () {
    return view('terms');
})->name('terms');

Route::middleware(['auth'])->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle/{id}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', 'App\Http\Controllers\NotificationController@index')->name('notifications');
    Route::post('/notifications/{id}/mark-read', 'App\Http\Controllers\NotificationController@markAsRead')->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', 'App\Http\Controllers\NotificationController@markAllRead')->name('notifications.markAllRead');
    
    // Make sure you have the download route
    Route::get('/materials/{material}/download', 'App\Http\Controllers\MaterialController@download')->name('materials.download');
});


Route::post('/materials/{material}/rate', [RatingController::class, 'store'])->name('materials.rate')->middleware('auth');
Route::get('/materials/{material}/remove-rating', [MaterialController::class, 'removeRating'])->name('materials.removeRating');

Route::post('/materials/{material}/comment', [CommentController::class, 'store'])->name('comments.store');

Route::delete('/comments/{id}', [App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');

Route::get('/materials/{id}/view', [MaterialController::class, 'view'])->name('materials.view');


// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Universal App Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Panel
    Route::prefix('admin')->middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // KYC Approvals
        Route::get('/kyc', [\App\Http\Controllers\AdminController::class, 'kycList'])->name('admin.kyc.list');
        Route::post('/kyc/{id}/approve', [\App\Http\Controllers\AdminController::class, 'kycApprove'])->name('admin.kyc.approve');
        Route::post('/kyc/{id}/reject', [\App\Http\Controllers\AdminController::class, 'kycReject'])->name('admin.kyc.reject');

        // Material Approvals
        Route::get('/materials', [\App\Http\Controllers\AdminController::class, 'materialsList'])->name('admin.materials.list');
        Route::post('/materials/{id}/approve', [\App\Http\Controllers\AdminController::class, 'materialApprove'])->name('admin.materials.approve');
        Route::post('/materials/{id}/reject', [\App\Http\Controllers\AdminController::class, 'materialReject'])->name('admin.materials.reject');
    });

    // KYC Submission
    Route::get('/kyc/submit', [\App\Http\Controllers\KycController::class, 'create'])->name('kyc.submit');
    Route::post('/kyc/store', [\App\Http\Controllers\KycController::class, 'store'])->name('kyc.store');

    // Payments (Auth required to initiate)
    Route::get('/pricing', [\App\Http\Controllers\PaymentController::class, 'pricing'])->name('pricing');
    Route::post('/payment/initiate', [\App\Http\Controllers\PaymentController::class, 'initiatePayment'])->name('payment.initiate');
    Route::get('/payment/checkout/mock', [\App\Http\Controllers\PaymentController::class, 'mockCheckout'])->name('payment.mock_checkout');
    Route::get('/payment/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');

    // AI Features
    Route::post('/ai/material/{id}/summarize', [\App\Http\Controllers\AiController::class, 'summarize'])->name('ai.summarize');
    Route::post('/ai/material/{id}/keypoints', [\App\Http\Controllers\AiController::class, 'keypoints'])->name('ai.keypoints');
    Route::post('/ai/material/{id}/ask', [\App\Http\Controllers\AiController::class, 'ask'])->name('ai.ask');
    Route::post('/ai/chat', [\App\Http\Controllers\AiController::class, 'chat'])->name('ai.chat');

    // Materials
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials');
    Route::get('/materials/{id}', [MaterialController::class, 'show'])->name('materials.show');

    // Upload
    Route::get('/upload', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');

    // Download
    Route::get('/materials/download/{id}', [DownloadController::class, 'download'])->name('materials.download');

    // Delete Material
    Route::delete('/materials/{id}', [MaterialController::class, 'destroy'])->name('materials.destroy');

    // My Materials
    Route::get('/my-materials', [MyMaterialsController::class, 'index'])->name('my-materials');

    //profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/academic', [ProfileController::class, 'updateAcademic'])->name('profile.academic.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

});

// API Webhook for Paychangu (No CSRF / Auth required)
Route::post('/api/paychangu/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');
