<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guests_can_not_create_a_post()
    {
        $attributes = Post::factory()->raw();

        $this->post(route('posts.store'), $attributes)->assertRedirect('login');
    }

    /**
     * @test
     */
    public function only_users_with_create_post_ability_can_create_a_post()
    {
        $this->signIn();

        $attributes = Post::factory()->raw();

        $this->post(route('posts.store'), $attributes)->assertForbidden();
    }

    /**
     * @test
     */
    public function post_requires_image()
    {
        $this->signIn()->ability('create-news');

        $attributes = Post::factory()->news()->raw(['image' => '']);

        $response = $this->post(route('posts.store'), $attributes);

        $response->assertSessionHasErrors('image');
    }

    /**
     * @test
     */
    public function post_requires_main_title()
    {
        $this->signIn()->ability('create-news');

        $attributes = Post::factory(['main_title' => ''])->raw();

        $response = $this->post(route('posts.store'), $attributes);

        $response->assertSessionHasErrors('main_title');
    }

    /**
     * @test
     */
    public function post_requires_secondary_title()
    {
        $this->signIn()->ability('create-news');

        $attributes = Post::factory(['secondary_title' => ''])->raw();

        $response = $this->post(route('posts.store'), $attributes);

        $response->assertSessionHasErrors('secondary_title');
    }

    /**
     * @test
     */
    public function post_requires_context()
    {
        $this->signIn()->ability('create-news');

        $attributes = Post::factory(['context' => ''])->raw();

        $response = $this->post(route('posts.store'), $attributes);

        $response->assertSessionHasErrors('context');
    }

    /**
     * @test
     */
    public function post_requires_type()
    {
        $this->signIn()->ability('create-news');

        $attributes = Post::factory(['type' => ''])->raw();

        $response = $this->post(route('posts.store'), $attributes);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function users_can_create_a_post()
    {
        $this->signIn()->ability('create-news');

        $attributes = Post::factory()->raw([
            'user_id' => auth()->id(),
            'image'   => UploadedFile::fake()->image('image.jpg'),
        ]);

        $this->post(route('posts.store'), $attributes)->assertViewIs('post.preview');

        $attributes['image'] = 'images/post/'.$attributes['image']->hashName();

        Storage::disk('public')->assertExists($attributes['image']);

        $this->assertDatabaseHas('posts', $attributes);
    }
}
