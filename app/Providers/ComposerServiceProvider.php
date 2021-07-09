<?php

namespace App\Providers;

use App\Http\ViewComposers\ButtonNewPostComposer;
use App\Http\ViewComposers\NavbarComposer;
use App\Http\ViewComposers\PostComposer;
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
        View::composer(['post.show','tweet.show'],PostComposer::class);

        View::composer(['sections.navbar'],NavbarComposer::class);

        View::composer(['layouts.app','index.index'],ButtonNewPostComposer::class);
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
