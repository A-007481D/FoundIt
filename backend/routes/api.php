<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageReportController;
use App\Http\Controllers\MessageNotificationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ItemDetectiveController;
use App\Http\Controllers\Admin\SettingsController;

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

// re-Broadcast channels auth endpoint (JWT via auth:api guard)
Broadcast::routes(['middleware' => ['auth:api']]);

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/password/email', [AuthController::class, 'sendResetLink']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
});

// Basic connectivity test
Route::get('/ping', function() {
    return response()->json(['message' => 'pong']);
});

// Item Detective public routes
Route::post('/item-detective/search', [ItemDetectiveController::class, 'search']);
Route::post('/item-detective/save-query', [ItemDetectiveController::class, 'saveQuery']);
Route::get('/item-detective/debug', [ItemDetectiveController::class, 'debug']);

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
    Route::prefix('message-notifications')->group(function () {
        Route::get('/', [MessageNotificationController::class, 'getNotifications']);
        Route::post('/{notificationId}/read', [MessageNotificationController::class, 'markAsRead']);
        Route::post('/read-all', [MessageNotificationController::class, 'markAllRead']);
    });

    // notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead']);

    // Item Detective routes (authenticated)
    // Route::post('/item-detective/search', [ItemDetectiveController::class, 'search']); // Moved to public routes

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
    // Match routes
    Route::get('/matches', [\App\Http\Controllers\MatchController::class, 'index']);
    
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
        
        // Settings
        Route::get('/settings', [SettingsController::class, 'index']);
        Route::put('/settings', [SettingsController::class, 'update']);
        
        // Activity logs
        Route::get('/activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index']);
        Route::get('/activity-logs/actions', [\App\Http\Controllers\Admin\ActivityLogController::class, 'getActionTypes']);
        Route::get('/activity-logs/entities', [\App\Http\Controllers\Admin\ActivityLogController::class, 'getEntityTypes']);
        Route::get('/activity-logs/categories', [\App\Http\Controllers\Admin\ActivityLogController::class, 'getCategories']);
        
        // User Sessions
        Route::get('/sessions', [\App\Http\Controllers\Admin\UserSessionController::class, 'index']);
        Route::get('/sessions/users/{userId}', [\App\Http\Controllers\Admin\UserSessionController::class, 'userSessions']);
        Route::post('/sessions/{sessionId}/terminate', [\App\Http\Controllers\Admin\UserSessionController::class, 'terminateSession']);
        Route::post('/sessions/users/{userId}/terminate-all', [\App\Http\Controllers\Admin\UserSessionController::class, 'terminateUserSessions']);
        Route::post('/sessions/cleanup', [\App\Http\Controllers\Admin\UserSessionController::class, 'cleanupExpiredSessions']);
    });
});
