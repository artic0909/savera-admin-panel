<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::useBootstrapFive();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('frontend.includes.header', function ($view) {
            $view->with('menuCategories', \App\Models\Category::where('menu', true)->orderBy('id', 'asc')->take(8)->get());
            $view->with('homeSettings', \App\Models\HomePageSetting::all()->pluck('value', 'key'));
        });

        \Illuminate\Support\Facades\View::composer('frontend.includes.footer', function ($view) {
            $view->with('categories', \App\Models\Category::where('footer', true)->orderBy('id', 'asc')->get());
        });
    }
}
