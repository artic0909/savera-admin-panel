<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\View::composer('frontend.includes.header', function ($view) {
            $view->with('menuCategories', \App\Models\Category::where('menu', true)->orderBy('id', 'asc')->take(8)->get());
        });

        \Illuminate\Support\Facades\View::composer('frontend.includes.footer', function ($view) {
            $view->with('categories', \App\Models\Category::where('footer', true)->orderBy('id', 'asc')->get());
        });
    }
}
