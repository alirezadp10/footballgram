<?php

namespace App\Http\ViewComposers;

use Gate;
use Illuminate\View\View;

class ButtonNewPostComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $response = [];

        $response['buttonNewPost']['canCreateUserContent'] = FALSE;

        $response['buttonNewPost']['canCreateNews'] = FALSE;

        $response['buttonNewPost']['canCreateTweet'] = FALSE;

        if (Gate::allows('create-user-content')) {
            $response['buttonNewPost']['canCreateUserContent'] = TRUE;
        }

        if (Gate::allows('create-tweet')) {
            $response['buttonNewPost']['canCreateTweet'] = TRUE;
        }

        if (Gate::allows('create-news')) {
            $response['buttonNewPost']['canCreateNews'] = TRUE;
        }

        if (isset($view['response'])) {
            $view['response'] = array_merge($response, $view['response']);
        } else {
            $view['response'] = $response;
        }

    }

}
