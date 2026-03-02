<?php

use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProfessionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->group(function () {
    Route::middleware('throttle:100,1')->group(function () {
        Route::post('/auth/register', [AuthController::class, 'register']);
        Route::post('/auth/login', [AuthController::class, 'login']);
    });

    Route::middleware('verify.api.key')->prefix('partners')->name('partners.')->group(function () {
        Route::get('/users', [PartnerController::class, 'users']);
        Route::get('/users/{user}', [PartnerController::class, 'user']);
        Route::get('/stats', [PartnerController::class, 'stats']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/permissions', [UserController::class, 'permissions']);

        Route::middleware('role:admin')->prefix('admin')->name('api.admin.')->group(function () {
            Route::apiResource('users', UserController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
            Route::get('roles', [UserController::class, 'roles']);
        });
    });

    Route::get('/offres', [OfferController::class, 'index']);
    Route::get('/offres/{offer}', [OfferController::class, 'show']);

    Route::get('/entreprises', [CompanyController::class, 'index']);
    Route::get('/entreprises/{company}', [CompanyController::class, 'show']);

    Route::get('/metiers', [ProfessionController::class, 'index']);
    Route::get('/metiers/{profession}', [ProfessionController::class, 'show']);
    Route::get('/metiers/{profession}/formations', [ProfessionController::class, 'formations']);

    Route::get('/formations', [FormationController::class, 'index']);
    Route::get('/formations/{formation}', [FormationController::class, 'show']);
    Route::get('/formations/random', [FormationController::class, 'random'])->name('formations.random');

    Route::get('/actualites', [NewsController::class, 'index']);
    Route::get('/actualites/{news}', [NewsController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/chatbot/ask', [ChatbotController::class, 'ask']);
        Route::get('/chatbot/history', [ChatbotController::class, 'history']);

        Route::get('/candidatures', [ApplicationController::class, 'index']);
        Route::post('/candidatures', [ApplicationController::class, 'store']);
        Route::get('/candidatures/{application}', [ApplicationController::class, 'show']);
    });
});
