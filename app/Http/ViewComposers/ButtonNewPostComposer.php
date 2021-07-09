<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ButtonNewPostComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view['can_create_user_content'] = Gate::allows('create-user-content');

        $view['can_create_news'] = Gate::allows('create-tweet');

        $view['can_create_tweet'] = Gate::allows('create-news');
    }

}
