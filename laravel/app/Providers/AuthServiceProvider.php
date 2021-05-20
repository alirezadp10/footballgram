<?php

namespace App\Providers;

use App\UserAction;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $userActions = [
            'create-comment',
            'create-news',
            'create-user-content',
            'edit-news',
            'delete-news',
            'edit-user-content',
            'delete-user-content',
            'slide-post',
            'chief-choice',
            'create-tweet',
            'delete-tweet',
            'edit-tweet',
            'manage-broadcast-schedule',
            'manage-survey',
        ];
        foreach ($userActions as $userAction) {
            Gate::define($userAction,function ($user) use ($userAction) {
                return DB::table('user_actions_pivot_users')
                         ->join('user_actions','user_actions.id','user_actions_pivot_users.user_action_id')
                         ->where('user_actions_pivot_users.user_id',$user->id)
                         ->where('user_actions.title',$userAction)
                         ->count();
            });
        }
    }
}
