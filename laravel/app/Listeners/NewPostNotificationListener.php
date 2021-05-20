<?php

namespace App\Listeners;

use App\Comment;
use App\Notifications\NewPostNotification;
use App\Notifications\ReplyNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class NewPostNotificationListener
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
//            $avatar = count($event->auth_user->images) ? Storage::url($event->auth_user->images[0]->sm) : '/images/userPhoto.png';
            foreach ($event->auth_user->followers as $follower){
                $follower->users
                    ->timeLine()
                    ->create([
                        'post_type' => 'App\News',
                        'post_id'   => $event->post['id'],
                    ]);
//                $follower->users->notify(new NewPostNotification(
//                    'news',
//                    $event->post['id'],
//                    route('news.show',$event->post['slug']),
//                    $avatar,
//                    $event->auth_user->name . ' مطلب جدید ارسال کرده است:',
//                    $event->post['secondary_title'] . ' ' . $event->post['main_title']
//                ));
            }
        }
        if ($event->type == 'userContent'){
//            $avatar = count($event->auth_user->images) ? Storage::url($event->auth_user->images[0]->sm) : '/images/userPhoto.png';
            foreach ($event->auth_user->followers as $follower){
                $follower->users
                    ->timeLine()
                    ->create([
                        'post_type' => 'App\UserContent',
                        'post_id'   => $event->post['id'],
                    ]);
//                $follower->users->notify(new NewPostNotification(
//                    'userContent',
//                    $event->post['id'],
//                    route('user-contents.show',$event->post['slug']),
//                    $avatar,
//                    $event->auth_user->name . ' مطلب جدید ارسال کرده است:',
//                    $event->post['secondary_title'] . ' ' . $event->post['main_title']
//                ));
            }
        }
    }
}
