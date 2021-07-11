<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavbarComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        if (!Auth::check()) {
            return;
        }

        $auth = auth()->user()->load('unreadNotifications');

        $view['navbar'] = [
            'url'                 => route('users.home'),
            'name'                => $auth->name,
            'avatar'              => $auth->image,
            'notifications_count' => $auth->unreadNotifications()->count(),
        ];
    }
}
