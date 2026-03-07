<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        View::composer('*', function ($view) {
            $user = Auth::user();
            $favoritesCount = $user ? $user->favorites()->count() : 0;
            $view->with('favoritesCount', $favoritesCount);
        });

         if (config('app.env') === 'production') {
        \URL::forceScheme('https');
    }
    }
}
