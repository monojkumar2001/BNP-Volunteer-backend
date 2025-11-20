<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\EventsController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\OpinionController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VolunteerController;

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');
Route::post('/auth/verify', [AuthController::class, 'verify']);

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/update/user', [UserController::class, 'updateUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
});



Route::post('/volunteer', [VolunteerController::class, 'store']);
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{slug}', [NewsController::class, 'singleNews']);
Route::get('/events', [EventsController::class, 'index']);
Route::get('/event/{slug}', [EventsController::class, 'singleEvent']);
Route::get('/gallery', [GalleryController::class, 'index']);
Route::get('/gallery/category/{slug}', [GalleryController::class, 'getByCategory']);
Route::get('/search', [SearchController::class, 'search']);
Route::post('/contact', [ContactUsController::class, 'store']);
Route::post('/opinion', [OpinionController::class, 'store']);
