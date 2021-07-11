<?php

namespace App\Listeners;

use App\Events\PostReleaseEvent;

class PushToTimelineListener
{
    /**
     * Handle the event.
     *
     * @param PostReleaseEvent $event
     *
     * @return void
     */
    public function handle(PostReleaseEvent $event)
    {
        $event->user->followers->each(function ($follower) use ($event) {
            $follower->postTimeline()->attach($event->model);
        });
    }
}
