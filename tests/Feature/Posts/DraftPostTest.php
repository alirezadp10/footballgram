<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DraftPostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function post_can_be_drafted()
    {
        $this->signIn();

        $post = Post::factory()->create();

        $this->put(route('posts.draft', ['slug' => $post->slug]));

        $this->assertEquals('DRAFT', Post::find($post->id)->status);
    }

    /**
     * @test
     */
    public function released_post_can_not_draft()
    {
        $this->signIn();

        $post = Post::factory()->create(['status' => 'RELEASE']);

        $this->put(route('posts.draft', ['slug' => $post->slug]));

        $this->assertNotEquals('DRAFT', Post::find($post->id)->status);
    }
}
