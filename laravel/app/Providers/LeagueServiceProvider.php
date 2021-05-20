<?php

namespace App\Providers;

use App\Services\LeagueService;
use Illuminate\Support\ServiceProvider;

class LeagueServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind("LeagueService",function(){
            return new LeagueService();
        });
    }
}
