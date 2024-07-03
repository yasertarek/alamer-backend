<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;

use App\Http\Controllers\HomeController;

Route::get('/home', [HomeController::class, 'index']);
    


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('blogs/{blog}/comments', [CommentController::class, 'store']);
    Route::post('blogs/{blog}/reactions', [ReactionController::class, 'store']);

    Route::post('/blogs', [BlogController::class, 'store']);
    Route::put('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
});
Route::get('/blogs', [BlogController::class, 'index']);

// Route for fetching recommended blogs
Route::get('blogs/recommendations', [BlogController::class, 'recommendations']);
Route::get('blogs/slugs', [BlogController::class, 'allSlugs']);

// Route::get('/blogs/search', [BlogController::class, 'search']);
// Route::get('/blogs/{id}', [BlogController::class, 'show']);
// Route for fetching a single blog by slug
Route::get('blogs/{slug}', [BlogController::class, 'show']);

Route::get('blogs/{blog}/comments', [CommentController::class, 'index']);
Route::get('blogs/{blog}/reactions', [ReactionController::class, 'index']);

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{slug}', [ServiceController::class, 'show']);
Route::get('/services/search', [ServiceController::class, 'search']);



Route::prefix('admin')->group(function () {
    Route::post('register', [AdminController::class, 'store']);
    Route::post('login', [AdminController::class, 'login']);
    Route::post('logout', [AdminController::class, 'logout']);

    Route::middleware(['auth:sanctum', RoleMiddleware::class . ':supervisor,moderator'])->group(function () {
        Route::apiResource('blogs', BlogController::class);
        Route::apiResource('services', ServiceController::class);
    });

    Route::middleware(['auth:sanctum', RoleMiddleware::class . ':supervisor'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('admins', AdminController::class);
    });

    // Route::middleware(['auth:admin', 'role:supervisor'])->group(function () {
    //     Route::apiResource('users', UserController::class);
    //     Route::post('users/{user}/suspend', [UserController::class, 'suspend']);
    // });
});