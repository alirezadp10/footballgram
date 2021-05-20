<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TimeLine extends Model{

    protected $table = 'time_line';

    protected $fillable = [
        'post_type',
        'post_id'
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public static function getForUser($user_id, $take = 9, $offset = 0)
    {
        return
            DB::table('time_line')
              ->select(
                  DB::raw('COALESCE(COALESCE(news.id,user_contents.id),"") AS id'),
                  DB::raw('COALESCE(COALESCE(news.slug,user_contents.slug),"") AS slug'),
                  DB::raw('COALESCE(COALESCE(news.main_title,user_contents.main_title),"") AS main_title'),
                  DB::raw('COALESCE(COALESCE(news.secondary_title,user_contents.secondary_title),"") AS secondary_title'),
                  DB::raw('COALESCE(COALESCE(news.like,user_contents.like),"") AS count_like'),
                  DB::raw('COALESCE(COALESCE(GROUP_CONCAT(distinct CONCAT(likes_1.user_id)), ""),"") AS users_liked_news'),
                  DB::raw('COALESCE(COALESCE(GROUP_CONCAT(distinct CONCAT(likes_2.user_id)), ""),"") AS users_liked_user_content'),
                  DB::raw('COALESCE(COALESCE(GROUP_CONCAT(distinct CONCAT(dislikes_1.user_id)), ""),"") AS users_disliked_news'),
                  DB::raw('COALESCE(COALESCE(GROUP_CONCAT(distinct CONCAT(dislikes_2.user_id)), ""),"") AS users_disliked_user_content'),
                  DB::raw('COALESCE(COALESCE(news.dislike,user_contents.dislike),"") AS count_dislike'),
                  DB::raw('COALESCE(COALESCE(images_1.md,images_2.md),"") AS image'),
                  DB::raw('COALESCE(COALESCE(users_1.id,users_2.id),"") AS author_id'),
                  DB::raw('COALESCE(COALESCE(users_1.name,users_2.name),"") AS author_name'),
                  DB::raw('COALESCE(time_line.post_type,"") AS type')
              )
              ->leftjoin('news', function ($join) {
                  $join->on('news.id', 'time_line.post_id')
                       ->where('time_line.post_type', 'App\News')
                       ->join('users as users_1', 'users_1.id', 'news.user_id')
                       ->leftJoin('likes as likes_1', function ($join) {
                           $join->on('likes_1.likeable_id', 'news.id')
                                ->where('likes_1.likeable_type', 'App\News');
                       })
                       ->leftJoin('dislikes as dislikes_1', function ($join) {
                           $join->on('dislikes_1.dislikeable_id', 'news.id')
                                ->where('dislikes_1.dislikeable_type', 'App\News');
                       })
                       ->leftJoin('images as images_1', function ($join) {
                           $join->on('images_1.imageable_id', 'news.id')
                                ->where('images_1.imageable_type', 'App\News');
                       });
              })
              ->leftjoin('user_contents', function ($join) {
                  $join->on('user_contents.id', 'time_line.post_id')
                       ->where('time_line.post_type', 'App\UserContent')
                       ->join('users as users_2', 'users_2.id', 'user_contents.user_id')
                       ->leftJoin('likes as likes_2', function ($join) {
                           $join->on('likes_2.likeable_id', 'user_contents.id')
                                ->where('likes_2.likeable_type', 'App\UserContent');
                       })
                       ->leftJoin('dislikes as dislikes_2', function ($join) {
                           $join->on('dislikes_2.dislikeable_id', 'user_contents.id')
                                ->where('dislikes_2.dislikeable_type', 'App\UserContent');
                       })
                       ->leftJoin('images as images_2', function ($join) {
                           $join->on('images_2.imageable_id', 'user_contents.id')
                                ->where('images_2.imageable_type', 'App\UserContent');
                       });
              })
              ->where('time_line.user_id', $user_id)
              ->groupBy('user_contents.id','news.id')
              ->take($take)
              ->offset($offset)
              ->orderBy('id', 'desc')
              ->get()
              ->toArray();

    }

}
