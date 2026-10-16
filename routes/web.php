<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\MatchmakingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PostReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThreadController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Reddit-Style Feed-First: Linimasa Utama langsung di rute GET /
Route::get('/', [FeedController::class, 'index'])->name('feed.index');
Route::get('/posts/{post}', [FeedController::class, 'show'])->name('posts.show');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit')->middleware('throttle:10,1');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Onboarding (Mandatory step for new users)
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    // Core Sirkelku Modules (Requires completion of onboarding)
    Route::middleware('onboarded')->group(function () {

        // Modul 1: Nongkrong Yuk (Social Feed)
        Route::get('/feed', [FeedController::class, 'index'])->name('feed.auth');
        Route::post('/posts', [FeedController::class, 'store'])->name('posts.store')->middleware('throttle:60,1');
        Route::put('/posts/{post}', [FeedController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}', [FeedController::class, 'destroy'])->name('posts.destroy');
        Route::post('/posts/{post}/like', [FeedController::class, 'toggleLike'])->name('posts.like');
        Route::post('/posts/{post}/comment', [FeedController::class, 'storeComment'])->name('posts.comment')->middleware('throttle:60,1');
        Route::post('/posts/{id}/report', [PostReportController::class, 'store'])->name('posts.report')->middleware('throttle:15,1');

        // Modul 2: Satu Sirkel (Komunitas / Circles)
        Route::get('/sirkel', [CommunityController::class, 'index'])->name('communities.index');
        Route::get('/sirkel/create', [CommunityController::class, 'create'])->name('communities.create');
        Route::post('/sirkel', [CommunityController::class, 'store'])->name('communities.store')->middleware('throttle:30,1');
        Route::get('/sirkel/{slug}', [CommunityController::class, 'show'])->name('communities.show');
        Route::post('/sirkel/{community}/join', [CommunityController::class, 'join'])->name('communities.join');
        Route::post('/sirkel/{community}/leave', [CommunityController::class, 'leave'])->name('communities.leave');

        // Modul 3: Tongkrongan.id (Forum Diskusi)
        Route::get('/forum', [ThreadController::class, 'index'])->name('threads.index');
        Route::get('/forum/create', [ThreadController::class, 'create'])->name('threads.create');
        Route::post('/forum', [ThreadController::class, 'store'])->name('threads.store')->middleware('throttle:30,1');
        Route::get('/forum/{thread}', [ThreadController::class, 'show'])->name('threads.show');
        Route::post('/forum/{thread}/comment', [ThreadController::class, 'storeComment'])->name('threads.comment')->middleware('throttle:60,1');
        Route::post('/forum/{thread}/pin', [ThreadController::class, 'togglePin'])->name('threads.pin');

        // Modul 4: Teman Main (Quick Matchmaking)
        Route::get('/teman-main', [MatchmakingController::class, 'index'])->name('matchmaking.index');
        Route::post('/teman-main/request', [MatchmakingController::class, 'sendRequest'])->name('matchmaking.request')->middleware('throttle:30,1');
        Route::post('/teman-main/requests/{matchRequest}/respond', [MatchmakingController::class, 'respondRequest'])->name('matchmaking.respond');

        // Modul 5: Profil & Pengaturan
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/credentials', [ProfileController::class, 'updateCredentials'])->name('profile.credentials.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Modul 6: Pusat Notifikasi
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-read', [NotificationController::class, 'markAllRead'])->name('notifications.markRead');

        // Modul 7: Direct Messages
        Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/check-incoming', [\App\Http\Controllers\MessageController::class, 'checkIncoming'])->name('messages.checkIncoming');
        Route::get('/messages/{user:username}/sync', [\App\Http\Controllers\MessageController::class, 'sync'])->name('messages.sync');
        Route::get('/messages/{user:username}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{user:username}', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store')->middleware('throttle:60,1');
        Route::delete('/messages/{message}', [\App\Http\Controllers\MessageController::class, 'destroy'])->name('messages.destroy');
    });
});

// Profile View Route (Accessible by guests & authenticated users; locked view for private non-friends)
Route::get('/profile/{username?}', [ProfileController::class, 'show'])->name('profile.show');

