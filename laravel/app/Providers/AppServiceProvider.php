<?php

namespace App\Providers;

use App\News;
use App\Observers\NewsObserver;
use App\Observers\UserContentObserver;
use App\UserContent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Passport::routes();

        Passport::tokensExpireIn(now()->addDays(15));

        Passport::refreshTokensExpireIn(now()->addDays(30));

        Blade::directive('role',function ($role) {

            $condition = 0;

            // check if the user is authenticated
            if (Auth::check()) {
                // check if the user has a subscription
                $condition = Auth::user()->role == $role ? 1 : 0;
            }

            return "<?php if ($condition) { ?>";
        });

        Blade::directive('end',function () {
            return "<?php } ?>";
        });

        Carbon::setLocale('fa');

        Carbon::setWeekStartsAt(Carbon::SATURDAY);

        Carbon::setWeekEndsAt(Carbon::FRIDAY);

        News::observe(NewsObserver::class);

        UserContent::observe(UserContentObserver::class);

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
