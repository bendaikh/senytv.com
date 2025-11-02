<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\BlogController as UserBlogController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Middleware\DetectUserLanguage;
use App\Http\Controllers\Admin\TosController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\FrontendManagementController;
use App\Http\Controllers\Admin\ChannelsListController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\TosViewerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;


// User Routes
Route::middleware([DetectUserLanguage::class])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('blog', [UserBlogController::class, 'index'])->name('blog.index');
    Route::get('blog/{slug}', [UserBlogController::class, 'blogShow'])->name('blog.show');
    Route::get('channels', [HomeController::class, 'channels'])->name('channels.index');
    Route::middleware('throttle:30,1')->get('/channels/load/{region}/{country}', [HomeController::class, 'loadChannels'])->name('channels.load');
    Route::middleware('throttle:30,1')->get('/channels/search', [HomeController::class, 'searchChannels'])->name('channels.search');

    Route::get('privacy-policy', [TosViewerController::class, 'privacy'])->name('privacy');
    Route::get('terms-of-use', [TosViewerController::class, 'terms'])->name('terms');
    Route::get('refund-policy', [TosViewerController::class, 'refund'])->name('refund');

    // Payment Routes
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('checkout/{planId}', [PaymentController::class, 'checkout'])->name('checkout');
        Route::post('process', [PaymentController::class, 'processCheckout'])->name('process');
        Route::get('success', [PaymentController::class, 'success'])->name('success');
        Route::get('cancel', [PaymentController::class, 'cancel'])->name('cancel');
        Route::get('transactions', [PaymentController::class, 'transactions'])->name('transactions');
        Route::get('transactions/{id}', [PaymentController::class, 'transactionDetail'])->name('transaction.detail');
    });

    // Customer Authentication Routes
    Route::middleware('guest:web')->group(function () {
        Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
        Route::post('login', [CustomerAuthController::class, 'login'])->name('customer.login.submit');
        Route::get('register', [CustomerAuthController::class, 'showRegisterForm'])->name('customer.register');
        Route::post('register', [CustomerAuthController::class, 'register'])->name('customer.register.submit');
    });

    Route::middleware('auth:web')->post('logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

    // Customer Dashboard Routes (Protected)
    Route::middleware('auth:web')->prefix('customer')->name('customer.')->group(function () {
        Route::get('dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::get('payments', [CustomerDashboardController::class, 'payments'])->name('payments');
        Route::get('plans', [CustomerDashboardController::class, 'plans'])->name('plans');
        Route::get('tickets', [CustomerDashboardController::class, 'tickets'])->name('tickets');
        Route::get('tickets/create', [CustomerDashboardController::class, 'createTicket'])->name('tickets.create');
        Route::post('tickets', [CustomerDashboardController::class, 'storeTicket'])->name('tickets.store');
        Route::get('tickets/{id}', [CustomerDashboardController::class, 'showTicket'])->name('tickets.show');
    });

    // Payment Webhook (outside DetectUserLanguage middleware for API compatibility)
});

// Payment Webhook Route (public, no language middleware)
Route::post('webhooks/payment', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Admin Routes
Route::prefix('backend')->name('admin.')->group(function () {
    // Authentication Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/', [AdminAuthController::class, 'login'])->name('authenticate');
        Route::get('password/reset', [ForgotPasswordController::class, 'index'])->name('password.request');
        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email.send');
        Route::get('password/reset/{token}', [ResetPasswordController::class, 'index'])->name('password.reset.form');
        Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset.submit');
    });

    // Protected Routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::post('/cache/clear', [DashboardController::class, 'clearCache'])->name('cache.clear');

        // CRUD Resource Routes
        Route::resources([
            'clients' => ClientController::class,
            'blogs' => AdminBlogController::class,
            'plans' => PlanController::class,
            'subscriptions' => SubscriptionController::class,
            'faqs' => FaqController::class,
            'payment-methods' => PaymentMethodController::class,
            'sliders' => FrontendManagementController::class,
            'channels' => ChannelsListController::class,
            'tickets' => TicketController::class,
        ]);

        // Ticket update route (PUT method)
        Route::put('tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');

        Route::resource('languages', ContentController::class)->only(['index']);
        Route::put('languages/{language}/{file}', [ContentController::class, 'update'])->name('languages.update');

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('color-setup', [SettingController::class, 'colorSetup'])->name('color-setup.index');
        Route::put('color-setup', [SettingController::class, 'updateColor'])->name('color-setup.update');

        Route::post('social-media', [SettingController::class, 'socialMediaStore'])->name('social-media.store');
        Route::delete('social-media/{id}', [SettingController::class, 'socialMediaDestroy'])->name('social-media.destroy');

        Route::get('profile', [AdminAuthController::class, 'showProfile'])->name('profile');
        Route::put('profile', [AdminAuthController::class, 'updateProfile'])->name('profile.update');

        Route::get('tos', [TosController::class, 'index'])->name('tos.index');
        Route::post('tos/privacy', [TosController::class, 'savePolicy'])->name('tos.privacy.save');
        Route::post('tos/terms', [TosController::class, 'saveTerms'])->name('tos.terms.save');
        Route::post('tos/refund', [TosController::class, 'saveRefund'])->name('tos.refund.save');

        Route::get('landing-page', [FrontendManagementController::class, 'LandingPage'])->name('landing-page.index');

        Route::get('partners', [FrontendManagementController::class, 'PartnersIndex'])->name('partners.index');
        Route::post('partners', [FrontendManagementController::class, 'PartnersStore'])->name('partners.store');
        Route::put('partners/{id}', [FrontendManagementController::class, 'PartnersUpdate'])->name('partners.update');
        Route::delete('partners/{id}', [FrontendManagementController::class, 'PartnersDestroy'])->name('partners.destroy');

        Route::get('images-manager', [FrontendManagementController::class, 'ImagesManagerIndex'])->name('images-manager.index');
        Route::put('images-manager', [FrontendManagementController::class, 'ImagesManageUpdate'])->name('images-manager.update');
    });
});
