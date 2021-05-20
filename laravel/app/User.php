<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string
 * @property string bio
 * @property string mobile
 * @property string username
 * @property string score
 * @property string count_news
 * @property string count_user_contents
 * @property string count_likes_given
 * @property string count_dislikes_given
 * @property string count_comments_taken
 * @property string count_comments_given
 * @property string count_followers
 * @property string count_followings
 * @property string instagram_id
 * @property string telegram_id
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $role
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserAction[] $userActions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'mobile',
        'username',
        'score',
        'count_news',
        'count_user_contents',
        'count_likes_given',
        'count_dislikes_given',
        'count_comments_taken',
        'count_comments_given',
        'count_followers',
        'count_followings',
        'instagram_id',
        'telegram_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function userActions()
    {
        return $this->belongsToMany(
            UserAction::class,
            'user_actions_pivot_users',
            'user_id',
            'user_action_id'
        );
    }

    public function news()
    {
        return $this->hasMany(News::class, 'user_id');
    }

    public function userContents()
    {
        return $this->hasMany(UserContent::class, 'user_id');
    }

    public function tweet()
    {
        return $this->hasMany(Tweet::class, 'user_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function likeNews()
    {
        return $this->morphedByMany(News::class, 'likeable', 'likes')
                    ->select('news.id');
    }

    public function likeUserContents()
    {
        return $this->morphedByMany(UserContent::class, 'likeable', 'likes')
                    ->select('user_contents.id');
    }

    public function likeTweets()
    {
        return $this->morphedByMany(Tweet::class, 'likeable', 'likes')
                    ->select('tweets.id');
    }

    public function likeComments()
    {
        return $this->morphedByMany(Comment::class, 'likeable', 'likes')
                    ->select('comments.id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    public function dislikeNews()
    {
        return $this->morphedByMany(News::class, 'dislikeable', 'dislikes')
                    ->select('news.id');
    }

    public function dislikeUserContents()
    {
        return $this->morphedByMany(UserContent::class, 'dislikeable', 'dislikes')
                    ->select('user_contents.id');
    }

    public function dislikeTweets()
    {
        return $this->morphedByMany(Tweet::class, 'dislikeable', 'dislikes')
                    ->select('tweets.id');
    }

    public function dislikeComments()
    {
        return $this->morphedByMany(Comment::class, 'dislikeable', 'dislikes')
                    ->select('comments.id');
    }

    public function dislikes()
    {
        return $this->hasMany(Dislike::class, 'user_id');
    }

    public function following()
    {
        return $this->hasMany(Following::class,'follower_id');
    }

    public function followers()
    {
        return $this->hasMany(Following::class,'follow_up_id');
    }

    public function survey()
    {
        return $this->belongsToMany(Survey::class,'surveys_pivot_users')->withTimestamps();
    }

    /**
     * relation document
     * one user could has been many news in his time-line
     * one news could has been exist in many time-line of users
     */
    public function NewsTimeLine()
    {
        return $this->morphedByMany(
            News::class,
            'post',
            'time_line'
        );
    }

    /**
     * relation document
     * one user could has been many user-content in his time-line
     * one user-content could has been exist in many time-line of users
     */
    public function UserContentTimeLine()
    {
        return $this->morphedByMany(
            UserContent::class,
            'post',
            'time_line'
        );
    }

    /**
     * relation document
     * one user could has been many tweet in his time-line
     * one tweet could has been exist in many time-line of users
     */
    public function TweetTimeLine()
    {
        return $this->morphedByMany(
            Tweet::class,
            'post',
            'time_line'
        );
    }

    public function timeLine()
    {
        return $this->hasMany(TimeLine::class, 'user_id');
    }

    public static function getFollowers($username, $offset = 0)
    {
        return
            DB::table('followings')
              ->leftJoin('users as follow_up', 'follow_up.id', 'followings.follow_up_id')
              ->leftJoin('users as follower', 'follower.id', 'followings.follower_id')
              ->leftJoin('images', function ($join) {
                  $join->on('images.imageable_id', 'follower.id')
                       ->where('images.imageable_type', 'App\User');
              })
              ->leftJoin(
                  'followings as followersOfFollower',
                  'followersOfFollower.follow_up_id',
                  'follower.id')
              ->select(
                  'follower.id',
                  'follower.name',
                  'follower.username',
                  'follower.count_followers',
                  'follower.count_followings',
                  'follower.count_news',
                  'follower.count_user_contents',
                  'follower.username',
                  'images.sm',
                  DB::raw('GROUP_CONCAT(followersOfFollower.follower_id) as followersOfFollower')
              )
              ->offset($offset)
              ->where('follow_up.name', '=', $username)
              ->orderBy('follower.count_followers', 'DESC')
              ->groupBy('follower.id')
              ->take(9)
              ->get();
    }

    public static function getFollowings($username, $offset = 0)
    {
        return
            DB::table('followings')
              ->leftJoin('users as follower', 'follower.id', 'followings.follower_id')
              ->leftJoin('users as follow_up', 'follow_up.id', 'followings.follow_up_id')
              ->leftJoin('images', function ($join) {
                  $join->on('images.imageable_id', 'follow_up.id')
                       ->where('images.imageable_type', 'App\User');
              })
              ->leftJoin(
                  'followings as followersOfFollowing',
                  'followersOfFollowing.follow_up_id',
                  'follow_up.id')
              ->select(
                  'follow_up.id',
                  'follow_up.name',
                  'follow_up.username',
                  'follow_up.count_followers',
                  'follow_up.count_followings',
                  'follow_up.count_news',
                  'follow_up.count_user_contents',
                  'follow_up.username',
                  'images.sm',
                  DB::raw('GROUP_CONCAT(followersOfFollowing.follower_id) as followersOfFollowing')
              )
              ->offset($offset)
              ->where('follower.name', '=', $username)
              ->orderBy('follow_up.count_followers', 'DESC')
              ->groupBy('follow_up.id')
              ->take(9)
              ->get();
    }

    public static function getNotifications($offset = 0, $take = 10)
    {
        return
            DB::table('notifications')
              ->join('users', 'users.id', '=', 'notifications.notifiable_id')
              ->leftJoin('users as users_1', function ($join) {
                  $join->on('users_1.id', 'notifications.data->meta_id')
                       ->leftJoin('images as images_1', function ($join) {
                           $join->on('images_1.imageable_id', 'users_1.id')
                                ->where('images_1.imageable_type', 'App\User');
                       });
              })
              ->leftJoin('news as news_1', function ($join) {
                  $join->on('news_1.id', 'notifications.data->meta_id')
                       ->leftJoin('images as images_2', function ($join) {
                           $join->on('images_2.imageable_id', 'news_1.user_id')
                                ->where('images_2.imageable_type', 'App\User');
                       });
              })
              ->leftJoin('user_contents as user_contents_1', function ($join) {
                  $join->on('user_contents_1.id', 'notifications.data->meta_id')
                       ->leftJoin('images as images_3', function ($join) {
                           $join->on('images_3.imageable_id', 'user_contents_1.user_id')
                                ->where('images_3.imageable_type', 'App\User');
                       });
              })
              ->leftJoin('comments as comments_1', function ($join) {
                  $join->on('comments_1.id', 'notifications.data->meta_id')
                       ->leftJoin('images as images_4', function ($join) {
                           $join->on('images_4.imageable_id', 'comments_1.user_id')
                                ->where('images_4.imageable_type', 'App\User');
                       })
                       ->leftJoin('news as news_2', function ($join) {
                           $join->on('news_2.id', 'comments_1.commentable_id')
                                ->where('comments_1.commentable_type', 'App\News');
                       });
              })
              ->leftJoin('comments as comments_2', function ($join) {
                  $join->on('comments_2.id', 'notifications.data->meta_id')
                       ->leftJoin('images as images_5', function ($join) {
                           $join->on('images_5.imageable_id', 'comments_2.user_id')
                                ->where('images_5.imageable_type', 'App\User');
                       })
                       ->leftJoin('user_contents as user_contents_2', function ($join) {
                           $join->on('user_contents_2.id', 'comments_2.commentable_id')
                                ->where('comments_2.commentable_type', 'App\UserContent');
                       });
              })
              ->where('notifications.notifiable_id', '=', Auth::id())
              ->select(
                  'notifications.data as notifications_data',
                  'users_1.id as following_id',
                  'users_1.name as following_name',
                  'images_1.sm as following_avatar',
                  'news_1.slug as news_slug',
                  'images_2.sm as news_user_avatar',
                  'user_contents_1.slug as user_content_slug',
                  'images_3.sm as user_content_user_avatar',
                  'news_2.slug as comment_news_slug',
                  'images_4.sm as comment_news_user_avatar',
                  'user_contents_2.slug as comment_user_content_slug',
                  'images_5.sm as comment_user_content_user_avatar'
              )
              ->offset($offset)
              ->take($take)
              ->orderBy('notifications.created_at', 'DESC')
              ->get();
    }

    public static function getUsersHaveMustFollower($take = 10, $offset = 0)
    {
        return
            DB::table('users')
              ->leftJoin('images', function ($join){
                  $join->on('images.imageable_id', 'users.id')
                       ->where('images.imageable_type', 'App\User');
              })
              ->take($take)
              ->offset($offset)
              ->orderBy('users.count_followers','DESC')
              ->select(
                  'users.id',
                  'users.name',
                  'users.count_followers',
                  'users.count_followings',
                  DB::raw('users.count_user_contents + users.count_news as count_posts'),
                  'images.sm as avatar'
              )
              ->get()
              ->toArray();
    }

}
