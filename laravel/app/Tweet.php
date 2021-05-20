<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function rand;
use function str_random;

/**
 * App\Tweet
 *
 * @property int $id
 * @property string $context
 * @property string $slug
 * @property string $like
 * @property string $dislike
 * @property string $report
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
class Tweet extends Model
{
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'context',
        'slug',
        'like',
        'dislike',
        'report',
        'view',
        'comment',
        'status',
    ];


    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes()
    {
        return $this->morphToMany(User::class, 'likeable', 'likes')
                    ->select(['users.id']);
    }

    public function dislikes()
    {
        return $this->morphToMany(User::class, 'dislikeable', 'dislikes')
                    ->select(['users.id']);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'method' => function () {
                    return str_random(20);
                },
            ],
        ];
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables');
    }

    /**
     * relation document
     * one user could has been many tweet in his time-line
     * one tweet could has been exist in many time-line of users
     */
    public function timeLine()
    {
        return $this->morphToMany(User::class, 'post', 'time_line')
                    ->select(['users.id']);
    }

    public static function getTweetWithComments($slug)
    {
        return DB::select("SELECT 
                              post.id,
                              post.slug,
                              post.context,
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
                            FROM tweets AS post
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
                            INNER JOIN users AS post_writer ON post_writer.id = post.user_id
                            LEFT JOIN images AS post_writer_image ON post_writer_image.imageable_id = post_writer.id
                            AND post_writer_image.imageable_type = ?
                            WHERE post.slug = ?
                            AND post.status = ?
                          ",[
            'App\User',
            'App\Comment',
            'App\Comment',
            'App\Tweet',
            'App\Tweet',
            'App\Tweet',
            'App\User',
            $slug,
            'RELEASE'
        ])[0];
    }

}
