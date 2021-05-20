<?php

namespace App\Listeners;

use App\Comment;
use App\Notifications\ReplyNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class ReplyNotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->type == 'news'){
            $commentReplied = Comment::with('user.images','news')
                                     ->where('id', request('parent_id'))
                                     ->get()
                                     ->first();
            $commentReplied->user->notify(new ReplyNotification(
                'newsReply',
                $event->comment->id,
                route('news.show', $commentReplied->news->slug),
                count($commentReplied->user->images) ? Storage::url($commentReplied->user->images[0]->sm) : '/images/userPhoto.png',
                $event->auth_user->name . ' کامنت شما را پاسخ داده است:',
                $event->comment->context
            ));
        }
        if ($event->type == 'userContent'){
            $commentReplied = Comment::with('user.images','userContents')
                                     ->where('id', request('parent_id'))
                                     ->get()
                                     ->first();

            $commentReplied->user->notify(new ReplyNotification(
                'userContentReply',
                $event->comment->id,
                route('user-contents.show', $commentReplied->userContents->slug),
                count($commentReplied->user->images) ? Storage::url($commentReplied->user->images[0]->sm) : '/images/userPhoto.png',
                $event->auth_user->name . ' کامنت شما را پاسخ داده است:',
                $event->comment->context
            ));

        }
    }
}
