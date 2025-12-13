<?php

use Illuminate\Support\Facades\Route;
// Controllers
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteSettings;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AppConfigController;

// Middlewares
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\OptionalAuth;
// use App\Http\Middleware\EnsureBlogOwner;
use App\Http\Controllers\NavbarController;

use App\Http\Controllers\VisitController;

Route::get('/visits', [VisitController::class, 'index']);
Route::post('/track-visit', [VisitController::class, 'track']);

Route::post('/seo/analyze', [SeoController::class, 'analyze']);

Route::middleware([OptionalAuth::class])->get('/home', [HomeController::class, 'index']);
Route::get('/navbar', [NavbarController::class, 'index']);


// Route::middleware(['auth:sanctum', EnsureBlogOwner::class])->group(function() {

// });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('sitemap', [PageController::class, 'sitemap']);

Route::get('prerender-routes', [PageController::class, 'prerenderRoutes']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::post('blogs/{blogId}/comments', [CommentController::class, 'store']);
    Route::post('blogs/{blogId}/reactions', [ReactionController::class, 'store']);
    Route::put('blogs/{blogId}/reactions/update', [ReactionController::class, 'update']);
    Route::delete('blogs/{blogId}/reactions/delete', [ReactionController::class, 'destroy']);

    // Route::post('/user/blogs', [BlogController::class, 'store']);
    // Route::put('/user/blogs/{id}', [BlogController::class, 'update']);
    // Route::delete('/user/blogs/{id}', [BlogController::class, 'destroy']);


    Route::get('/get-self-blogs', [BlogController::class, 'getSelfBlogs']);

    
});
Route::get('/blogs', [BlogController::class, 'index']);


Route::get('/cats', [App\Http\Controllers\CatsController::class, 'index']);
Route::get('/cats/{id}', [App\Http\Controllers\CatsController::class, 'show']);
Route::get('/cats/search', [App\Http\Controllers\CatsController::class, 'search']);
Route::post('/cats', [App\Http\Controllers\CatsController::class, 'store']);
Route::put('/cats/{id}', [App\Http\Controllers\CatsController::class, 'update']);
Route::delete('/cats/{id}', [App\Http\Controllers\CatsController::class, 'destroy']);

// Route for fetching recommended blogs
Route::get('blogs/recommendations', [BlogController::class, 'recommendations']);
// Route::get('blogs/slugs', [BlogController::class, 'allSlugs']);

// Route::get('/blogs/search', [BlogController::class, 'search']);
// Route::get('/blogs/{id}', [BlogController::class, 'show']);
// Route for fetching a single blog by slug
Route::get('blogs/{slug}', [BlogController::class, 'show']);

Route::get('blogs/{blog}/comments', [CommentController::class, 'index']);
Route::get('blogs/{blog}/reactions', [ReactionController::class, 'index']);

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{slug}', [ServiceController::class, 'show']);
// Route::get('/services/search', [ServiceController::class, 'search']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('user')->group(function () {
    });
});

Route::middleware(['auth:sanctum', RoleMiddleware::class . ':supervisor'])->group(function () {
    Route::prefix('sup')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('admins', AdminController::class);
    });
});

Route::middleware(['auth:sanctum', RoleMiddleware::class . ':moderator'])->group(function () {
    Route::prefix('mod')->group(function () {

    });
});

Route::prefix('admin')->group(function () {
    Route::post('register', [AdminController::class, 'store']);
    Route::post('login', [AdminController::class, 'login']);
    Route::middleware(['auth:sanctum', RoleMiddleware::class . ':moderator,supervisor'])->group(function () {
        Route::post('logout', [AdminController::class, 'logout']);
        Route::get('blogs', [BlogController::class, 'getAllBlogs']);
        Route::post('blogs', [BlogController::class, 'store']);
        Route::put('blogs/update/{id}', [BlogController::class, 'update']);
        Route::post('blogs/approve', [BlogController::class, 'approve']);
        Route::delete('blogs/{id}', [BlogController::class, 'destroy']);
        


        // Route::apiResource('blogs', BlogController::class);
        Route::apiResource('services', ServiceController::class);
        Route::get('website-settings', [WebsiteSettings::class, 'index']);
        Route::post('add-navbar', [NavbarController::class, 'store']);
        Route::delete('delete-navbar/{id}', [NavbarController::class, 'destroy']);
    });
});


Route::prefix('admin')->group(function () {


    // Route::middleware(['auth:admin', 'role:supervisor'])->group(function () {
    //     Route::apiResource('users', UserController::class);
    //     Route::post('users/{user}/suspend', [UserController::class, 'suspend']);
    // });
});


Route::get('/pages/{slug}', [PageController::class, 'show']);
Route::get('/pages', [PageController::class, 'index']);
Route::middleware([OptionalAuth::class])->get('/app-config', [AppConfigController::class, 'getConfig']);
