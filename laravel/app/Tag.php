<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    protected $fillable = [
        'name','count'
    ];

    public function news()
    {
        return $this->morphedByMany(News::class, 'taggable', 'taggables');
    }

    public function userContents()
    {
        return $this->morphedByMany(UserContent::class, 'taggable', 'taggables');
    }

    public function tweets()
    {
        return $this->morphedByMany(Tweet::class, 'taggable', 'taggables');
    }

    public function comments()
    {
        return $this->morphedByMany(Comment::class, 'taggable', 'taggables');
    }

    public function taggables()
    {
        return $this->hasMany(Taggable::class, 'tag_id');
    }

    public static function getNewsRelatedToTag($name, $offset = 0)
    {
        $tags = DB::table('tags')
                  ->select(
                      'news.id',
                      'news.slug as slug',
                      'news.main_title as main_title',
                      'news.secondary_title as secondary_title',
                      'news.like as count_like',
                      'news.dislike as count_dislike',
                      DB::raw('COALESCE(COALESCE(GROUP_CONCAT(distinct CONCAT(likes.user_id)), ""),"") AS users_liked'),
                      DB::raw('COALESCE(COALESCE(GROUP_CONCAT(distinct CONCAT(dislikes.user_id)), ""),"") AS users_disliked'),
                      'users.name as author_name',
                      'users.id as author_id',
                      'taggables.taggable_type',
                      'images.md as image'
                  )
                  ->join('taggables', function ($join) {
                      $join->on('taggables.tag_id', 'tags.id')
                           ->where('taggables.taggable_type', 'App\News');
                  })
                  ->leftJoin('news', function ($join) {
                      $join->on('news.id', 'taggables.taggable_id')
                           ->where('taggables.taggable_type', 'App\News')
                           ->join('users', 'users.id', 'news.user_id')
                           ->leftJoin('images as images', function ($join) {
                               $join->on('images.imageable_id', 'news.id')
                                    ->where('images.imageable_type', 'App\News');
                           })
                           ->leftJoin('likes', function ($join) {
                               $join->on('likes.likeable_id', 'news.id')
                                    ->where('likes.likeable_type', 'App\News');
                           })
                           ->leftJoin('dislikes', function ($join) {
                               $join->on('dislikes.dislikeable_id', 'news.id')
                                    ->where('dislikes.dislikeable_type', 'App\News');
                           });
                  })
                  ->where('tags.name', '=', $name)
                  ->where('news.status', 'release')
                  ->orderBy('taggables.created_at', 'desc')
                  ->groupBy('news.id')
                  ->offset($offset)
                  ->take(9)
                  ->get();
        return $tags;
    }

    public static function getNewsRelatedToTagWithoutImage($name, $take = 9, $offset = 0)
    {
        $tags = DB::table('tags')
                  ->join('taggables', function ($join) {
                      $join->on('taggables.tag_id', 'tags.id')
                           ->where('taggables.taggable_type', 'App\News');
                  })
                  ->leftJoin('news', function ($join) {
                      $join->on('news.id', 'taggables.taggable_id')
                           ->where('taggables.taggable_type', 'App\News');
                  })
                  ->select(
                      'news.slug as slug',
                      'news.created_at',
                      'news.main_title as mainTitle',
                      'news.secondary_title as secondaryTitle'
                  )
                  ->where('tags.name', '=', $name)
                  ->where('news.status', 'release')
                  ->orderBy('taggables.created_at', 'desc')
                  ->groupBy('news.id')
                  ->offset($offset)
                  ->take($take)
                  ->get();
        return $tags;
    }

    public static function getUserContentsRelatedToTag($name, $offset = 0)
    {
        $tags = DB::table('tags')
                  ->select(
                      'user_contents.id',
                      'user_contents.slug as slug',
                      'user_contents.main_title as main_title',
                      'user_contents.secondary_title as secondary_title',
                      'user_contents.like as count_like',
                      'user_contents.dislike as count_dislike',
                      DB::raw('COALESCE(COALESCE(GROUP_CONCAT(distinct CONCAT(likes.user_id)), ""),"") AS users_liked'),
                      DB::raw('COALESCE(COALESCE(GROUP_CONCAT(distinct CONCAT(dislikes.user_id)), ""),"") AS users_disliked'),
                      'users.name as author_name',
                      'users.id as author_id',
                      'taggables.taggable_type',
                      'images.md as image'
                  )
                  ->join('taggables', function ($join) {
                      $join->on('taggables.tag_id', 'tags.id')
                           ->where('taggables.taggable_type', 'App\UserContent');
                  })
                  ->leftJoin('user_contents', function ($join) {
                      $join->on('user_contents.id', 'taggables.taggable_id')
                           ->where('taggables.taggable_type', 'App\UserContent')
                           ->join('users', 'users.id', 'user_contents.user_id')
                           ->leftJoin('images as images', function ($join) {
                               $join->on('images.imageable_id', 'user_contents.id')
                                    ->where('images.imageable_type', 'App\UserContent');
                           })
                           ->leftJoin('likes', function ($join) {
                               $join->on('likes.likeable_id', 'user_contents.id')
                                    ->where('likes.likeable_type', 'App\UserContent');
                           })
                           ->leftJoin('dislikes', function ($join) {
                               $join->on('dislikes.dislikeable_id', 'user_contents.id')
                                    ->where('dislikes.dislikeable_type', 'App\UserContent');
                           });
                  })
                  ->where('tags.name', '=', $name)
                  ->where('user_contents.status', 'release')
                  ->orderBy('taggables.created_at', 'desc')
                  ->groupBy('user_contents.id')
                  ->offset($offset)
                  ->take(9)
                  ->get();

        return $tags;
    }

    public static function getCommentsRelatedToTag($name, $offset = 0)
    {
        $tags = DB::table('tags')
                  ->join('taggables', function ($join) {
                      $join->on('taggables.tag_id', 'tags.id')
                           ->where('taggables.taggable_type', 'App\Comment');
                  })
                  ->leftJoin('comments', function ($join) {
                      $join->on('comments.id', 'taggables.taggable_id')
                           ->join('users', 'users.id', 'comments.user_id')
                           ->leftJoin('images', function ($join) {
                               $join->on('images.imageable_id', 'users.id')
                                    ->where('images.imageable_type', 'App\User');
                           })
                           ->leftJoin('news', function ($join) {
                               $join->on('news.id', 'comments.commentable_id')
                                    ->where('comments.commentable_type', 'App\News');
                           })
                           ->leftJoin('user_contents', function ($join) {
                               $join->on('user_contents.id', 'comments.commentable_id')
                                    ->where('comments.commentable_type', 'App\UserContent');
                           });
                  })
                  ->leftJoin('likes', function ($join) {
                      $join->on('likes.likeable_id', 'comments.id')
                           ->where('likes.likeable_type', 'App\Comment');
                  })
                  ->leftJoin('dislikes', function ($join) {
                      $join->on('dislikes.dislikeable_id', 'comments.id')
                           ->where('dislikes.dislikeable_type', 'App\Comment');
                  })
                  ->select(
                      'taggables.taggable_type',
                      'comments.commentable_type',
                      'comments.id as id',
                      'comments.level as level',
                      'comments.like',
                      'comments.dislike',
                      'users.id as author_id',
                      'users.name as author_name',
                      'users.username as author_username',
                      'user_contents.slug as user_content_slug',
                      'news.slug as news_slug',
                      'images.sm as user_avatar',
                      'comments.context as context',
                      DB::raw('GROUP_CONCAT(likes.user_id) as users_likes_this'),
                      DB::raw('GROUP_CONCAT(dislikes.user_id) as users_dislikes_this')
                  )
                  ->where('tags.name', '=', $name)
                  ->groupBy('comments.id')
                  ->orderBy('taggables.created_at', 'desc')
                  ->offset($offset)
                  ->take(9)
                  ->get();

        return $tags;
    }

    public static function getAllRelatedToTag($name, $take = 9, $offset = 0)
    {
        $tags = DB::table('taggables')
                  ->join('tags', 'tags.id', '=', 'taggables.tag_id')
                  ->leftJoin('news as news_1', function ($join) {
                      $join->on('news_1.id', 'taggables.taggable_id')
                           ->join('users as users_1', 'users_1.id', 'news_1.user_id')
                           ->leftJoin('images as images_1', function ($join) {
                               $join->on('images_1.imageable_id', 'users_1.id')
                                    ->where('images_1.imageable_type', 'App\User');
                           })
                           ->where('taggables.taggable_type', 'App\News');
                  })
                  ->leftJoin('user_contents as user_contents_1', function ($join) {
                      $join->on('user_contents_1.id', 'taggables.taggable_id')
                           ->join('users as users_2', 'users_2.id', 'user_contents_1.user_id')
                           ->leftJoin('images as images_2', function ($join) {
                               $join->on('images_2.imageable_id', 'users_2.id')
                                    ->where('images_2.imageable_type', 'App\User');
                           })
                           ->where('taggables.taggable_type', 'App\UserContent');
                  })
                  ->leftJoin('comments', function ($join) {
                      $join->on('comments.id', 'taggables.taggable_id')
                           ->join('users as users_3', 'users_3.id', 'comments.user_id')
                           ->leftJoin('images as images_3', function ($join) {
                               $join->on('images_3.imageable_id', 'users_3.id')
                                    ->where('images_3.imageable_type', 'App\User');
                           })
                           ->leftJoin('news as news_2', function ($join) {
                               $join->on('news_2.id', 'comments.commentable_id')
                                    ->where('comments.commentable_type', 'App\News');
                           })
                           ->leftJoin('user_contents as user_contents_2', function ($join) {
                               $join->on('user_contents_2.id', 'comments.commentable_id')
                                    ->where('comments.commentable_type', 'App\UserContent');
                           })
                           ->where('taggables.taggable_type', 'App\Comment');
                  })
                  ->select(
                      'taggables.taggable_type',
                      'comments.commentable_type as comment_commentable_type',
                      'comments.id as comment_id',
                      'users_3.name as comment_writer',
                      'user_contents_2.slug as comment_user_contents_slug',
                      'news_2.slug as comment_news_slug',
                      'images_3.sm as comment_avatar',
                      'comments.context as comment_context',
                      'news_1.slug as news_slug',
                      'news_1.main_title as news_main_title',
                      'news_1.secondary_title as news_secondary_title',
                      'users_3.name as news_writer',
                      'images_1.sm as news_image',
                      'user_contents_1.slug as user_content_slug',
                      'user_contents_1.main_title as user_content_main_title',
                      'user_contents_1.secondary_title as user_content_secondary_title',
                      'users_2.name as user_content_writer',
                      'images_2.sm as user_content_image'
                  )
                  ->where('tags.name', '=', $name)
                  ->take(9)
                  ->get();

        return $tags;
    }

    public static function trends($date, $take)
    {
        return
            DB::table('tags')
              ->select(
                  'tags.name',
                  DB::raw('COUNT(taggables.id) as count')
              )
              ->join('taggables', 'taggables.tag_id', 'tags.id')
              ->where('taggables.created_at', '>', $date)
              ->orderBy('count', 'DESC')
              ->take($take)
              ->groupBy('tags.name')
              ->get()
              ->toArray();
    }
}
