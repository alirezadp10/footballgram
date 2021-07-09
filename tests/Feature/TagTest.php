<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function if_a_tag_is_repeated_it_should_not_be_re_created()
    {
        Tag::factory(['name' => 'messi'])->hasAttached(Post::factory()->count(5))->create();

        $this->assertEquals(1,Tag::whereName('messi')->count());
    }
}
