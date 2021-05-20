<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NavbarComposer
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

        if (Auth::check()) {

            $auth_user = Auth::user()->load('images','unreadNotifications');

            $response['navbar'] = [
                'url'    => route('users.index'),
                'name'   => $auth_user->name,
                'avatar' => count($auth_user->images) ? $auth_user->images[0]->xs : '/images/userPhoto.png',
            ];

            $response['navbar']['notifications_count'] = $auth_user->unreadNotifications()->count();

        }

        if (isset($view['response'])) {
            $view['response'] = array_merge($response, $view['response']);
        } else {
            $view['response'] = $response;
        }

    }
}
