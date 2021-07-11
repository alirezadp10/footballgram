<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LastNewsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function last_news_must_be_accessible()
    {
        Post::factory()->news()->released()->count(15)->create();

        $response = $this->get(route('posts.last'));

        $response->assertJson([
            'total' => 15,
        ]);

        $response->assertJsonStructure([
            'data' => [
                [
                    'title',
                    'time',
                    'url',
                ],
            ],
        ]);
    }
}
