<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\DiscoverController;

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

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // User profile
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::post('/profile/preferences', [UserController::class, 'updatePreferences']);
    
    // Matchmaking
    Route::get('/recommendations', [RecommendationController::class, 'getRecommendations']);
    Route::post('/matches/{user}', [MatchController::class, 'createMatch']);
    Route::get('/matches', [MatchController::class, 'getMatches']);
    Route::delete('/matches/{user}', [MatchController::class, 'unmatch']);
    
    // Discover Section (New Like System)
    Route::get('/discover', [DiscoverController::class, 'getDiscoverProfiles']);
    Route::post('/discover/like/{user}', [DiscoverController::class, 'likeProfile']);
    Route::delete('/discover/unlike/{user}', [DiscoverController::class, 'unlikeProfile']);
    Route::get('/discover/liked-by', [DiscoverController::class, 'getLikedByProfiles']);
    
    // Likes (Legacy - Deprecated)
    Route::post('/likes/{user}', [LikeController::class, 'like']);
    Route::delete('/likes/{user}', [LikeController::class, 'unlike']);
    Route::get('/likes', [LikeController::class, 'getLikes']);
    
    // Messages
    Route::post('/messages/send', [MessageController::class, 'sendMessage']);
    Route::get('/messages/{user}', [MessageController::class, 'getChatHistory']);
    Route::get('/messages', [MessageController::class, 'getConversations']);
}); 