<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostObserver
{
    public function created(Post $post)
    {
        if ($post->getAttribute('status') == 'RELEASED') {
            $post->user()->increment('count_posts');
        }
    }

    public function updated(Post $post)
    {
        if (isset($post->getChanges()['image'])) {
            Storage::disk('public')->delete($post->getOriginal('image'));
        }

        if (in_array('RELEASED',$post->getChanges())) {
            $post->user()->increment('count_posts');
        }
    }

    public function deleted(Post $post)
    {
        $post->tags()->delete();

        $post->comments()->delete();

        Storage::disk('public')->delete($post->image);

        $post->user()->update([
            'count_posts'          => DB::raw("\"count_posts\" - 1"),
            'count_likes_given'    => DB::raw("\"count_likes_given\" - $post->like"),
            'count_dislikes_given' => DB::raw("\"count_dislikes_given\" - $post->dislike"),
            'count_comments_given' => DB::raw("\"count_comments_given\" - $post->comment"),
        ]);
    }
}
