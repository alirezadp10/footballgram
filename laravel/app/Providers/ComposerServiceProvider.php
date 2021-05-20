<?php

namespace App\Providers;

use App\Http\ViewComposers\ButtonNewPostComposer;
use App\Http\ViewComposers\NavbarComposer;
use App\Http\ViewComposers\PostComposer;
use App\Http\ViewComposers\UserContentComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            [
                'post.news.show',
                'post.userContent.show',
                'post.tweet.show',
                'welcome.welcome'
            ], UserContentComposer::class
        );

        View::composer(
            ['post.news.show','post.tweet.show','post.userContent.show'], PostComposer::class
        );

        View::composer(
            ['sections.navbar'], NavbarComposer::class
        );

        View::composer(
            ['layouts.app','welcome.welcome'], ButtonNewPostComposer::class
        );

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}