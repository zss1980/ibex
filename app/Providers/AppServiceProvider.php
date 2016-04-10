<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Event;
use App\Page;

use App\Events\PageRouteCreated;
use App\Events\PageRouteUpdated;
use App\Events\PageRouteDeleted;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       /*Page::created(function ($newpage) {
        
            Event::fire(new PageRouteCreated($newpage));
        });

       /* Page::updated(function ($page) {
            Event::fire(new PageRouteUpdated($page));
        });

        Page::deleted(function ($page) {
            Event::fire(new PageRouteDeleted($page));
        });*/
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
