<?php

namespace App;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * App\UserAction
 *
 * @property int $id
 * @property string $title
 * @property string $main_title
 * @property string $secondary_title
 * @property string $context
 * @property string $slug
 * @property string $like
 * @property string $dislike
 * @property string $comment
 * @property string $view
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAction whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class News extends Model
{
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'main_title',
        'secondary_title',
        'context',
        'slug',
        'like',
        'dislike',
        'view',
        'comment',
        'dislike',
        'status',
    ];

    protected static function boot() {
        parent::boot();
        static::deleting(function($post) {
            $images = $post->images();
            !isset($images->first()->xs) ?: Storage::delete($images->first()->xs);
            !isset($images->first()->sm) ?: Storage::delete($images->first()->sm);
            !isset($images->first()->md) ?: Storage::delete($images->first()->md);
            !isset($images->first()->lg) ?: Storage::delete($images->first()->lg);
            !isset($images->first()->xl) ?: Storage::delete($images->first()->xl);
            $images->delete();
        });
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes()
    {
        return $this->morphToMany(User::class, 'likeable','likes')->select(['users.id']);
    }

    public function dislikes()
    {
        return $this->morphToMany(User::class, 'dislikeable','dislikes')->select(['users.id']);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'main_title'
            ]
        ];
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable','taggables');
    }

    /**
     * relation document
     * one user could has been many news in his time-line
     * one news could has been exist in many time-line of users
     */
    public function timeLine()
    {
        return $this->morphToMany(User::class, 'post','time_line')->select(['users.id']);
    }

    public static function hotNewsWithImage()
    {
        return
            DB::table('news')
              ->select(
                  "main_title as mainTitle",
                  "secondary_title as secondaryTitle",
                  "news.id",
                  "news.created_at",
                  "news.slug",
                  "images.sm"
              )
              ->join('images', 'images.imageable_id', '=', 'news.id')
              ->where('images.imageable_type', '=', 'App\News')
              ->where('news.created_at', '>=', Carbon::now()
                                                     ->subDay(3000))
              ->whereNull('news.deleted_at')
              ->where('news.status', 'release')
              ->orderBy('news.view', 'des')
              ->take(10)
              ->get()
              ->toArray();
    }

    public static function lastNews($take = 15, $offset = 0)
    {
        return
            DB::table('news')
              ->select(
                  "main_title as mainTitle",
                  "secondary_title as secondaryTitle",
                  "created_at",
                  "id",
                  "slug"
              )
              ->whereNull('news.deleted_at')
              ->where('news.status', 'release')
              ->orderBy('id', 'desc')
              ->offset($offset)
              ->take($take)
              ->get()
              ->toArray();
    }

    public static function hotNews($take = 10, $offset = 0)
    {
        return
            DB::table('news')
              ->select(
                  "main_title as mainTitle",
                  "secondary_title as secondaryTitle",
                  "created_at",
                  "id",
                  "slug"
              )
              ->whereNull('news.deleted_at')
              ->where('news.created_at', '>=', Carbon::now()
                                                     ->subDay(3000))
              ->where('news.status', 'release')
              ->orderBy('news.view', 'des')
              ->offset($offset)
              ->take($take)
              ->get()
              ->toArray();
    }

    public static function slider()
    {
        return
            DB::table('slider')
              ->join('images', 'images.imageable_id', '=', 'slider.news_id')
              ->where('images.imageable_type', '=', 'App\News')
              ->orderBy('order', 'asc')
              ->orderBy('slider.updated_at', 'desc')
              ->select(
                  'main_title',
                  'secondary_title',
                  'news_id',
                  'slug',
                  'first_tag',
                  'second_tag',
                  'third_tag',
                  'forth_tag',
                  "images.sm",
                  "images.lg"
              )
              ->get()
              ->toArray();
    }

    public static function chiefChoice()
    {
        return
            $chief_choices = DB::table('chief_choices')
                               ->join('images', 'images.imageable_id', '=', 'chief_choices.news_id')
                               ->where('images.imageable_type', '=', 'App\News')
                               ->select(
                                   'main_title',
                                   'secondary_title',
                                   'news_id',
                                   'slug',
                                   "images.md as image"
                               )
                               ->get()
                               ->toArray();
    }

    public static function getNewsWithComments($slug)
    {
        return DB::select("SELECT 
                              post.id,
                              post.slug,
                              post.main_title,
                              post.secondary_title,
                              post.context,
                              post_image.original AS main_photo,
                              post.`like` AS count_like,
                              post.dislike AS count_dislike,
                              post.view AS count_view,
                              post.comment AS count_comment,
                              post.created_at,
                              COALESCE(GROUP_CONCAT(distinct CONCAT(post_likes.user_id)), '') AS users_liked_post,
                              COALESCE(GROUP_CONCAT(distinct CONCAT(post_dislikes.user_id)), '') AS users_disliked_post,
                              post_writer.id AS author_id,
                              post_writer.name AS author_name,
                              post_writer_image.sm AS author_image,
                              post_image.imageable_type,
                              CONCAT(
                                  '[',
                                      GROUP_CONCAT(
                                        distinct CONCAT(
                                            '{',
                                                '\"id\"                     :   \"' , comments.id , '\",'
                                                '\"context\"                :   \"' , HEX(comments.context) , '\",'
                                                '\"parent\"                 :   \"' , comments.parent , '\",'
                                                '\"sub\"                    :   \"' , comments.sub , '\",'
                                                '\"level\"                  :   \"' , comments.level , '\",'
                                                '\"like\"                   :   \"' , comments.like , '\",'
                                                '\"dislike\"                :   \"' , comments.dislike , '\",'
                                                '\"report\"                 :   \"' , comments.report , '\",'
                                                '\"writer_id\"              :   \"' , comments.writer_id , '\",'
                                                '\"writer_name\"            :   \"' , comments.writer_name , '\",'
                                                '\"writer_photo\"           :   \"' , comments.writer_photo , '\",'
                                                '\"users_liked_comment\"    :   \"' , comments.users_liked_comment , '\",'
                                                '\"users_disliked_comment\" :   \"' , comments.users_disliked_comment , '\"'
                                            '}'
                                        )
                                      ),
                                  ']'
                              ) AS comments
                            FROM news AS post
                            LEFT JOIN (
                              SELECT
                                comments.`id`,
                                comments.`context`,
                                COALESCE(comments.`parent`, '') AS parent,
                                COALESCE(comments.`level`, '') AS level,
                                comments.`like` AS `like`,
                                comments.`dislike` AS `dislike`,
                                comments.`report` AS report,
                                users.`name` AS writer_name,
                                users.`id` AS writer_id,
                                COALESCE(user_comment_image.`sm`, '') AS writer_photo,
                                COALESCE(GROUP_CONCAT(distinct CONCAT(comment_likes.user_id)), '') AS users_liked_comment,
                                COALESCE(GROUP_CONCAT(distinct CONCAT(comment_dislikes.user_id)), '') AS users_disliked_comment,
                                comments.`commentable_id`,
                                comments.`commentable_type`,
                                comments.`like` - comments.`dislike` AS sub
                              FROM
                                comments
                              INNER JOIN users ON users.id = comments.user_id
                              LEFT JOIN images AS user_comment_image ON user_comment_image.imageable_id = users.id
                              AND user_comment_image.imageable_type = ?
                              LEFT JOIN likes AS comment_likes ON comment_likes.likeable_id = comments.id
                              AND comment_likes.likeable_type = ?
                              LEFT JOIN dislikes AS comment_dislikes ON comment_dislikes.dislikeable_id = comments.id
                              AND comment_dislikes.dislikeable_type = ?
                              GROUP BY comments.`id`
                              ORDER BY sub DESC 
                            ) AS comments ON comments.commentable_id = post.id
                            AND comments.commentable_type = ?
                            LEFT JOIN likes AS post_likes ON post_likes.likeable_id = post.id
                            AND post_likes.likeable_type = ?
                            LEFT JOIN dislikes AS post_dislikes ON post_dislikes.dislikeable_id = post.id
                            AND post_dislikes.dislikeable_type = ?
                            LEFT JOIN images AS post_image ON post_image.imageable_id = post.id
                            AND post_image.imageable_type = ?
                            INNER JOIN users AS post_writer ON post_writer.id = post.user_id
                            LEFT JOIN images AS post_writer_image ON post_writer_image.imageable_id = post_writer.id
                            AND post_writer_image.imageable_type = ?
                            WHERE post.slug = ?
                            AND post.status = ?
                          ",[
            'App\User',
            'App\Comment',
            'App\Comment',
            'App\News',
            'App\News',
            'App\News',
            'App\News',
            'App\User',
            $slug,
            'RELEASE'
        ])[0];
    }

    public static function getNewsWithoutComments($slug)
    {
        return DB::select("SELECT 
                              post.id,
                              post.slug,
                              post.main_title,
                              post.secondary_title,
                              post.context,
                              post_image.lg AS main_photo,
                              post.`like` AS count_like,
                              post.dislike AS count_dislike,
                              post.view AS count_view,
                              post.comment AS count_comment,
                              post.created_at,
                              COALESCE(GROUP_CONCAT(distinct CONCAT(post_likes.user_id)), '') AS users_liked_post,
                              COALESCE(GROUP_CONCAT(distinct CONCAT(post_dislikes.user_id)), '') AS users_disliked_post,
                              post_writer.id AS author_id,
                              post_writer.name AS author_name,
                              post_writer_image.sm AS author_image,
                              post_image.imageable_type
                            FROM news AS post
                            LEFT JOIN likes AS post_likes ON post_likes.likeable_id = post.id
                            AND post_likes.likeable_type = ?
                            LEFT JOIN dislikes AS post_dislikes ON post_dislikes.dislikeable_id = post.id
                            AND post_dislikes.dislikeable_type = ?
                            LEFT JOIN images AS post_image ON post_image.imageable_id = post.id
                            AND post_image.imageable_type = ?
                            INNER JOIN users AS post_writer ON post_writer.id = post.user_id
                            LEFT JOIN images AS post_writer_image ON post_writer_image.imageable_id = post_writer.id
                            AND post_writer_image.imageable_type = ?
                            WHERE post.slug = ?
                            AND post.status = ?
                          ",[
            'App\News',
            'App\News',
            'App\News',
            'App\User',
            $slug,
            'RELEASE'
        ])[0];
    }

}
