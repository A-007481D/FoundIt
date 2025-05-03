<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\MatcherInterface;
use App\Repositories\Contracts\ItemRepositoryInterface;
use App\Repositories\Contracts\ItemMatchRepositoryInterface;
use App\Repositories\EloquentItemRepository;
use App\Repositories\EloquentItemMatchRepository;
use App\Services\MatchingService;
use App\Services\SearchMatcher;
use App\Services\AttributeScorer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AttributeScorer::class, function() {
            return new AttributeScorer();
        });

        $this->app->bind(MatcherInterface::class, function ($app) {
            return config('matching.engine', 'legacy') === 'search'
                ? $app->make(SearchMatcher::class)
                : $app->make(MatchingService::class);
        });
        // Repositories
        $this->app->bind(ItemRepositoryInterface::class, EloquentItemRepository::class);
        $this->app->bind(ItemMatchRepositoryInterface::class, EloquentItemMatchRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\Item::observe(\App\Observers\ItemObserver::class);
    }
}
