<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Models\article;


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
        // Validator::extend('max_articles', function ($attribute, $value, $parameters) {
        //     $count = Article::where('blog_id', $value)->count();
        //     return $count < $parameters[0];
        // });
    }
}