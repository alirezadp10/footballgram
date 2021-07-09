<?php

namespace App\Providers;

use App\Events\DetectTagsEvent;
use App\Events\PostCommentEvent;
use App\Events\PostReleaseEvent;
use App\Listeners\DetectMentionsListener;
use App\Listeners\DetectTagsListener;
use App\Listeners\NotifyFollowersListener;
use App\Listeners\NotifyOnReplyListener;
use App\Listeners\PushToTimelineListener;
use App\Models\Comment;
use App\Models\Dislike;
use App\Models\Like;
use App\Models\Post;
use App\Observers\CommentObserver;
use App\Observers\DislikeObserver;
use App\Observers\LikeObserver;
use App\Observers\PostObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        PostReleaseEvent::class => [
            NotifyFollowersListener::class,
            PushToTimelineListener::class,
            DetectTagsListener::class,
        ],
        PostCommentEvent::class => [
            DetectTagsListener::class,
            DetectMentionsListener::class,
            NotifyOnReplyListener::class,
        ],
        DetectTagsEvent::class => [
            DetectTagsListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Post::observe(PostObserver::class);
        Comment::observe(CommentObserver::class);
        Like::observe(LikeObserver::class);
        Dislike::observe(DislikeObserver::class);
    }
}
