<?php

use App\Http\Controllers\Admin\ApiKeyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController as UserDashboardController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProfessionController;
use App\Http\Controllers\ProfileController;
use App\Models\Formation;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $formations = Formation::with(['skills', 'region'])
        ->inRandomOrder()
        ->take(4)
        ->get();

    return view('welcome', compact('formations'));
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:5,1');

    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

    Route::middleware(['role:manager'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'manager'])->name('dashboard');
    });

    Route::get('/offres', [OfferController::class, 'index'])->name('offers.index');
    Route::get('/offres/create', [OfferController::class, 'create'])->name('offers.create')->middleware('auth');
    Route::get('/offres/{offer}', [OfferController::class, 'show'])->name('offers.show');
    Route::post('/offres', [OfferController::class, 'store'])->name('offers.store')->middleware('auth');
    Route::get('/offres/{offer}/edit', [OfferController::class, 'edit'])->name('offers.edit')->middleware('auth');
    Route::put('/offres/{offer}', [OfferController::class, 'update'])->name('offers.update')->middleware('auth');
    Route::delete('/offres/{offer}', [OfferController::class, 'destroy'])->name('offers.destroy')->middleware('auth');

    Route::get('/entreprises', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/entreprises/{company}', [CompanyController::class, 'show'])->name('companies.show');
    Route::get('/entreprises/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/entreprises', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/entreprises/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/entreprises/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('/entreprises/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');

    Route::get('/metiers', [ProfessionController::class, 'index'])->name('professions.index');
    Route::get('/metiers/{profession}', [ProfessionController::class, 'show'])->name('professions.show');

    Route::get('/formations', [FormationController::class, 'index'])->name('formations.index');
    Route::get('/formations/{formation}', [FormationController::class, 'show'])->name('formations.show');

    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])->name('chatbot.ask');
    Route::get('/chatbot/history', [ChatbotController::class, 'history'])->name('chatbot.history');
    Route::delete('/chatbot/clear', [ChatbotController::class, 'clear'])->name('chatbot.clear');

    Route::get('/actualites', [NewsController::class, 'index'])->name('news.index');
    Route::get('/actualites/{news}', [NewsController::class, 'show'])->name('news.show');
    Route::post('/actualites/ask', [NewsController::class, 'ask'])->name('news.ask');

    Route::get('/candidatures', [ApplicationController::class, 'index'])->name('applications.index');
    Route::post('/candidatures', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/candidatures/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::put('/candidatures/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
    Route::post('/candidatures/{application}/withdraw', [ApplicationController::class, 'withdraw'])->name('applications.withdraw');
    Route::delete('/candidatures/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('/users', UserController::class);
        Route::resource('/roles', RoleController::class);

        Route::get('/api-keys', [ApiKeyController::class, 'index'])->name('api-keys.index');
        Route::get('/api-keys/create', [ApiKeyController::class, 'create'])->name('api-keys.create');
        Route::post('/api-keys', [ApiKeyController::class, 'store'])->name('api-keys.store');
        Route::get('/api-keys/{apiKey}/edit', [ApiKeyController::class, 'edit'])->name('api-keys.edit');
        Route::put('/api-keys/{apiKey}', [ApiKeyController::class, 'update'])->name('api-keys.update');
        Route::delete('/api-keys/{apiKey}', [ApiKeyController::class, 'destroy'])->name('api-keys.destroy');
        Route::post('/api-keys/{apiKey}/regenerate', [ApiKeyController::class, 'regenerate'])->name('api-keys.regenerate');
        Route::post('/api-keys/{apiKey}/toggle', [ApiKeyController::class, 'toggle'])->name('api-keys.toggle');

        Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    });
});
