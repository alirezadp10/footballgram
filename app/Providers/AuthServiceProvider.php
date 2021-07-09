<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $userActions = [
            'create-news',
            'create-user-content',
            'create-tweet',
            'create-comment',
            'manage-broadcast-schedule',
            'manage-survey',
            'manage-slide-post',
            'manage-chief-choice',
        ];

        foreach ($userActions as $userAction) {
            Gate::define($userAction,function ($user) use ($userAction) {
                return DB::table('users_abilities')
                         ->join('abilities','abilities.id','users_abilities.ability_id')
                         ->where('users_abilities.user_id',$user->id)
                         ->where('abilities.title',$userAction)
                         ->count();
            });
        }

        if (!$this->app->routesAreCached()) {
            Passport::routes();
        }
    }
}
