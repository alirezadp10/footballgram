<?php

namespace App\Observers;

use App\Events\NewPostNotificationEvent;
use App\UserContent;
use Illuminate\Support\Facades\Auth;

class UserContentObserver
{
    /**
     * Handle the news "created" event.
     *
     * @param  \App\UserContent  $userContent
     * @return void
     */
    public function created(UserContent $userContent)
    {
//        $auth_user = Auth::user()->load('images','followers.users');
//        event(new NewPostNotificationEvent($userContent->toArray(), 'userContent', $auth_user));
    }

    /**
     * Handle the news "updated" event.
     *
     * @param  \App\UserContent  $userContent
     * @return void
     */
    public function updated(UserContent $userContent)
    {
        //
    }

    /**
     * Handle the news "deleted" event.
     *
     * @param  \App\UserContent  $userContent
     * @return void
     */
    public function deleted(UserContent $userContent)
    {
        //
    }

    /**
     * Handle the news "restored" event.
     *
     * @param  \App\UserContent  $userContent
     * @return void
     */
    public function restored(UserContent $userContent)
    {
        //
    }

    /**
     * Handle the news "force deleted" event.
     *
     * @param  \App\UserContent  $userContent
     * @return void
     */
    public function forceDeleted(UserContent $userContent)
    {
        //
    }
}
