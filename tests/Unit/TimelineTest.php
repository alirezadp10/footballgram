<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimelineTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_user_have_post_timeline()
    {
        $mammad = User::factory()->has(User::factory()->hasPosts(5)->count(3),'followings')->create();

        $this->assertInstanceOf(Post::class,$mammad->postTimeline->first());

        $this->assertEquals(15,$mammad->postTimeline->count());
    }

    /**
     * @test
     */
    public function a_user_have_tweet_timeline()
    {
        $mammad = User::factory()->has(User::factory()->hasTweets(5)->count(3),'followings')->create();

        $this->assertInstanceOf(Tweet::class,$mammad->tweetTimeline->first());

        $this->assertEquals(15,$mammad->tweetTimeline->count());
    }
}
