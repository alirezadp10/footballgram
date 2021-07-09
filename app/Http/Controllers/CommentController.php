<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class CommentController extends Controller
{
    public function like(Comment $comment)
    {
        $this->authorize('like',$comment);

        if (!$comment->likes->contains(auth()->id())) {
            auth()->user()->like($comment);
        }

        if ($comment->dislikes->contains(auth()->id())) {
            auth()->user()->undislike($comment);
        }

        return response()->json([
            'like'    => $comment->like,
            'dislike' => $comment->dislike,
        ]);
    }

    public function dislike(Comment $comment)
    {
        $this->authorize('dislike',$comment);

        if (!$comment->dislikes->contains(auth()->id())) {
            auth()->user()->dislike($comment);
        }

        if ($comment->likes->contains(auth()->id())) {
            auth()->user()->unlike($comment);
        }

        return response()->json([
            'like'    => $comment->like,
            'dislike' => $comment->dislike,
        ]);
    }
}
