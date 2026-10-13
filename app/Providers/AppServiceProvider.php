<?php

namespace App\Providers;

use App\View\Composers\AdminComposer;
use App\View\Composers\SiteComposer;
use Illuminate\Support\Facades\View;
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
        View::composer('site', SiteComposer::class);
        View::composer('admin.layouts.app', AdminComposer::class);
    }
}
