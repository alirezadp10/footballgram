<?php

namespace App\Providers;

use App\Support\Macros\CustomKebabMacro;
use App\Support\Macros\CustomPaginateMacro;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->call(CustomPaginateMacro::class);

        app()->call(CustomKebabMacro::class);
    }
}
