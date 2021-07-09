<?php

namespace App\Listeners;

use App\Events\PostReleaseEvent;
use App\Notifications\NewPostNotification;

class NotifyFollowersListener
{
    /**
     * Handle the event.
     *
     * @param PostReleaseEvent $event
     * @return void
     */
    public function handle(PostReleaseEvent $event)
    {
        $event->user->followers->each->notify(new NewPostNotification($event->model));
    }
}
