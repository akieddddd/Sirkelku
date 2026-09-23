<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Community;
use App\Models\Thread;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        View::composer('layouts.app', function ($view) {
            $user = Auth::user();
            
            // Trending communities
            $trendingCommunities = Community::withCount('members')
                ->with('hobby')
                ->orderByDesc('members_count')
                ->take(4)
                ->get();

            // Hot forum threads
            $hotThreads = Thread::withCount('comments')
                ->with(['hobby', 'user'])
                ->orderByDesc('comments_count')
                ->take(4)
                ->get();

            // Suggested friends
            $suggestedFriends = collect();
            if ($user) {
                $myHobbyIds = $user->hobbies()->pluck('hobbies.id');
                $suggestedFriends = User::where('id', '!=', $user->id)
                    ->when($myHobbyIds->isNotEmpty(), function ($q) use ($myHobbyIds) {
                        $q->whereHas('hobbies', function ($h) use ($myHobbyIds) {
                            $h->whereIn('hobbies.id', $myHobbyIds);
                        });
                    })
                    ->with(['school', 'hobbies'])
                    ->inRandomOrder()
                    ->take(3)
                    ->get();

                if ($suggestedFriends->count() < 3) {
                    $more = User::where('id', '!=', $user->id)
                        ->whereNotIn('id', $suggestedFriends->pluck('id'))
                        ->with(['school', 'hobbies'])
                        ->inRandomOrder()
                        ->take(3 - $suggestedFriends->count())
                        ->get();
                    $suggestedFriends = $suggestedFriends->merge($more);
                }
            }

            $unreadCount = $user ? $user->unreadNotificationsCount() : 0;
            $unreadMessagesCount = $user ? $user->unreadMessagesCount() : 0;

            $view->with([
                'trendingCommunities' => $trendingCommunities,
                'hotThreads' => $hotThreads,
                'suggestedFriends' => $suggestedFriends,
                'unreadCount' => $unreadCount,
                'unreadMessagesCount' => $unreadMessagesCount,
            ]);
        });
    }
}
