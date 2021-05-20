<?php

namespace App\Http\ViewComposers;

use App\UserContent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserContentComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $userContents = UserContent::getUserContentForComposer(12);

        $response['userContents'] = [];

        foreach ($userContents as $userContent) {

            $separator = $userContent->secondary_title ? '؛' : '';

            $isLiked = FALSE;

            $isDisliked = FALSE;

            if (Auth::check() && in_array(Auth::id(), explode(',', $userContent->users_liked))){
                $isLiked = TRUE;
            }

            if (Auth::check() && in_array(Auth::id(), explode(',', $userContent->users_disliked))){
                $isDisliked = TRUE;
            }

            $response['userContents'][] = [
                'id'           => $userContent->id,
                'title'        => "{$userContent->main_title} {$separator} {$userContent->secondary_title}",
                'mainPhoto'    => !is_null($userContent->image) ? Storage::url($userContent->image) : "/images/twitter.png",
                'countLike'    => $userContent->like,
                'isLiked'      => $isLiked,
                'countDislike' => $userContent->dislike,
                'isDisliked'   => $isDisliked,
                'url'          => route('user-contents.show', [$userContent->slug]),
                'authorName'   => $userContent->name,
                'authorUrl'    => route('users.show', [
                    'id'   => $userContent->author_id,
                    'name' => $userContent->name,
                ]),
            ];

        }

        if (isset($view['response'])) {
            $view['response'] = array_merge($response, $view['response']);
        } else {
            $view['response'] = $response;
        }

    }
}
