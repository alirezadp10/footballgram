<?php

namespace App\Providers;

use App\Events\CreateTag;
use App\Events\DeleteTag;
use App\Events\NewPostNotificationEvent;
use App\Events\ReplyNotificationEvent;
use App\Listeners\CreateTagListener;
use App\Listeners\DeleteTagListener;
use App\Listeners\NewPostNotificationListener;
use App\Listeners\ReplyNotificationListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CreateTag::class                => [
            CreateTagListener::class,
        ],
        DeleteTag::class                => [
            DeleteTagListener::class,
        ],
        ReplyNotificationEvent::class   => [
            ReplyNotificationListener::class,
        ],
        NewPostNotificationEvent::class => [
            NewPostNotificationListener::class,
        ],
        SocialiteWasCalled::class       => [
            'SocialiteProviders\Telegram\TelegramExtendSocialite@handle',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
