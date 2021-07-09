<?php

namespace Tests\Feature\Users;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_should_can_see_his_homepage()
    {
        $this->signIn();

        $this->get(route('users.home'))->assertOk();
    }

    /**
     * @test
     */
    public function users_profile_can_accessible_for_anyone()
    {
        $user = User::factory()->create();

        $this->get(route('users.show',$user->username))->assertOk();
    }

    /**
     * @test
     */
    public function if_auth_unfollow_another_user_he_must_be_see_following_button_in_user_page()
    {
        $this->signIn();

        $user = User::factory()->create();

        $this->get(route('users.show',$user->username))->assertViewHas([
            'isFollowing' => FALSE,
        ]);
    }

    /**
     * @test
     */
    public function if_auth_follow_another_user_he_must_be_see_unfollowing_button_in_user_page()
    {
        $this->signIn();

        $user = User::factory()->create();

        auth()->user()->follow($user);

        $this->get(route('users.show',$user->username))->assertViewHas([
            'isFollowing' => TRUE,
        ]);
    }

    /**
     * @test
     */
    public function users_should_can_see_his_configuration_page()
    {
        $this->signIn();

        $this->get(route('users.configuration'))->assertOk();
    }

    /**
     * @test
     */
    public function user_can_see_his_post_timeline()
    {


        $mammad = User::factory()->has(User::factory()->hasPosts(5)->count(3),'followings')->create();

        $this->signIn($mammad);

        $this->get(route('users.posts-timeline'))->assertOk()->assertJsonStructure([
            "data" => [
                [
                    "id",
                    "title",
                    "image",
                    "countLike",
                    "isLiked",
                    "countDislike",
                    "isDisliked",
                    "url",
                    "user" => [
                        "name",
                        "url",
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function user_news_can_be_accessible()
    {
        $mammad = User::factory()->has(Post::factory()->news()->released()->count(30),'posts')->create();

        $response = $this->get(route('users.news',$mammad->username));

        $response->assertOk();

        $response->assertJsonCount(9,'data');

        $response->assertJsonStructure([
            "data" => [
                [
                    "title",
                    "image",
                    "url",
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function user_user_contents_can_be_accessible()
    {
        $mammad = User::factory()->has(Post::factory()->userContent()->released()->count(30),'posts')->create();

        $response = $this->get(route('users.user-contents',$mammad->username));

        $response->assertOk();

        $response->assertJsonCount(9,'data');

        $response->assertJsonStructure([
            "data" => [
                [
                    "title",
                    "image",
                    "url",
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function user_comments_can_be_accessible()
    {
        $mammad = User::factory()->hasComments(20)->create();

        $response = $this->get(route('users.comments',$mammad->username));

        $response->assertOk();

        $response->assertJsonCount(9,'data');

        $response->assertJsonStructure([
            "data" => [
                [
                    'context',
                    'id',
                    'url',
                    'like',
                    'dislike',
                    'isLiked',
                    'isDisliked',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function users_followers_list_must_be_accessible()
    {
        $mammad = User::factory()->hasFollowers(20)->create();

        $response = $this->get(route('users.followers',$mammad->username));

        $response->assertOk();

        $this->assertInstanceOf(Paginator::class,$response->viewData('response'));
    }

    /**
     * @test
     */
    public function users_followings_list_must_be_accessible()
    {
        $mammad = User::factory()->hasFollowings(20)->create();

        $response = $this->get(route('users.followings',$mammad->username));

        $response->assertOk();

        $this->assertInstanceOf(Paginator::class,$response->viewData('response'));
    }
}
