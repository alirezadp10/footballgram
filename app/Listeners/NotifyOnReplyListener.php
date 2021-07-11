<?php

namespace App\Listeners;

use App\Models\Comment;
use App\Notifications\ReplyNotification;

class NotifyOnReplyListener
{
    /**
     * Handle the event.
     *
     * @param object $event
     *
     * @return void
     */
    public function handle($event)
    {
        if ($event->comment->parent_id != null) {
            Comment::find($event->comment->parent_id)->user->notify(new ReplyNotification($event->comment));
        }
    }
}
