<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Settings;
use App\Models\User;
use App\Observers\ArticleObserver;
use App\Observers\CategoryObserver;
use App\Observers\UserRegisteredObserver;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Nette\Utils\Paginator;

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
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        //Mail Verifyı için ve loglama için kullanılıyor
        User::observe(UserRegisteredObserver::class);
        Category::observe(CategoryObserver::class);
        Article::observe(ArticleObserver::class);

        Carbon::setLocale(config("app.locale"));

        View::composer(['front.*', 'mail::header','email.*', 'layouts.admin.*'], function ($view) {
            $settings = Settings::first();
            $categories = Category::query()
                ->with('childCategories')
                ->where(['status' => 1])
                ->orderBy('order','DESC')
                ->get();
            $view->with('categories', $categories)
                ->with('settings', $settings);
        });
    }
}
