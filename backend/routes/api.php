<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageReportController;
use App\Http\Controllers\MessageNotificationController;

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
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/password/email', [AuthController::class, 'sendResetLink']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
});

// Protected routes
Route::middleware(['auth:api', 'verified'])->group(function () {
    // User routes
    Route::get('/user', [AuthController::class, 'user']);
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto']);
    Route::put('/profile/notifications', [ProfileController::class, 'updateNotificationPreferences']);
    Route::put('/profile/privacy', [ProfileController::class, 'updatePrivacySettings']);
    
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::get('/auth/user', [AuthController::class, 'getAuthUser']);

    // Chat routes
    Route::prefix('chat')->group(function () {
        Route::get('/conversations', [ChatController::class, 'getConversations']);
        Route::post('/conversations', [ChatController::class, 'createConversation']);
        Route::get('/conversations/{conversationId}/messages', [ChatController::class, 'getMessages']);
        Route::post('/conversations/{conversationId}/messages', [ChatController::class, 'sendMessage']);
    });

    // Message reports
    Route::post('/messages/{messageId}/report', [MessageReportController::class, 'report']);

    // Message notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [MessageNotificationController::class, 'index']);
        Route::post('/{notificationId}/read', [MessageNotificationController::class, 'markAsRead']);
        Route::post('/read-all', [MessageNotificationController::class, 'markAllAsRead']);
    });

    // Item routes for all authenticated users
    Route::prefix('items')->group(function () {
        Route::get('/', [\App\Http\Controllers\ItemController::class, 'index']);
        Route::get('/my-items', [\App\Http\Controllers\ItemController::class, 'userItems']);
        Route::post('/', [\App\Http\Controllers\ItemController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\ItemController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\ItemController::class, 'update']);
        Route::patch('/{id}/archive', [\App\Http\Controllers\ItemController::class, 'archive']);
        Route::patch('/{id}/restore', [\App\Http\Controllers\ItemController::class, 'restore']);
    });
    
    // Categories for item creation/filtering
    Route::get('/categories', [\App\Http\Controllers\ItemController::class, 'getCategories']);
    
    // Report routes for all authenticated users
    Route::post('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'store']);

    // Admin routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);
        
        // Users
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index']);
        Route::get('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'show']);
        Route::patch('/users/{id}/status', [\App\Http\Controllers\Admin\UserController::class, 'updateStatus']);
        Route::patch('/users/{id}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole']);
        Route::patch('/users/{id}/ban', [\App\Http\Controllers\Admin\UserController::class, 'ban']);
        Route::patch('/users/{id}/unban', [\App\Http\Controllers\Admin\UserController::class, 'unban']);
        Route::patch('/users/{id}/suspend', [\App\Http\Controllers\Admin\UserController::class, 'suspend']);
        Route::get('/users/stats', [\App\Http\Controllers\Admin\UserController::class, 'getStats']);
        
        // Items
        Route::get('/items', [\App\Http\Controllers\Admin\ItemController::class, 'index']);
        Route::get('/items/{id}', [\App\Http\Controllers\Admin\ItemController::class, 'show']);
        Route::patch('/items/{id}/status', [\App\Http\Controllers\Admin\ItemController::class, 'updateStatus']);
        Route::patch('/items/{id}/visibility', [\App\Http\Controllers\Admin\ItemController::class, 'updateVisibility']);
        Route::patch('/items/{id}/archive', [\App\Http\Controllers\Admin\ItemController::class, 'archive']);
        Route::delete('/items/{id}', [\App\Http\Controllers\Admin\ItemController::class, 'delete']);
        Route::get('/items/stats', [\App\Http\Controllers\Admin\ItemController::class, 'getStats']);
        
        // Reports
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index']);
        Route::get('/reports/{id}', [\App\Http\Controllers\Admin\ReportController::class, 'show']);
        Route::patch('/reports/{id}/status', [\App\Http\Controllers\Admin\ReportController::class, 'updateStatus']);
        Route::patch('/reports/{id}/resolve', [\App\Http\Controllers\Admin\ReportController::class, 'resolve']);
        Route::patch('/reports/{id}/dismiss', [\App\Http\Controllers\Admin\ReportController::class, 'dismiss']);
        Route::get('/reports/stats', [\App\Http\Controllers\Admin\ReportController::class, 'getStats']);
    });
});
