<?php

namespace Tests\Feature\Posts;

use App\Events\DetectTagsEvent;
use App\Models\Post;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdatePostTest extends TestCase
{
    use RefreshDatabase,WithFaker;

    /**
     * @test
     */
    public function guests_can_not_edit_or_update_a_post()
    {
        $post = Post::factory()->released()->create();

        $this->get(route('posts.edit',$post->slug))->assertRedirect('login');

        $this->patch(route('posts.update',$post->slug),[])->assertRedirect('login');
    }

    /**
     * @test
     */
    public function only_users_with_update_post_ability_can_edit_or_update_a_post()
    {
        $this->signIn();

        $post = Post::factory()->released()->create();

        $this->get(route('posts.edit',$post->slug))->assertForbidden();

        $this->patch(route('posts.update',$post->slug),[])->assertForbidden();
    }

    /**
     * @test
     */
    public function users_can_update_main_title_of_post()
    {
        $this->signIn()->ability('edit-news');

        $post = Post::factory()->released()->create();

        $main_title = $this->faker->sentence;

        $slug = SlugService::createSlug(Post::class,'slug',$main_title);

        $this->patch(route('posts.update',$post->slug),compact('main_title'))
             ->assertRedirect(route('posts.show',$slug));

        $this->assertEquals($main_title,Post::first()->main_title);
    }

    /**
     * @test
     */
    public function users_can_update_secondary_title_of_post()
    {
        $this->signIn()->ability('edit-news');

        $post = Post::factory()->released()->create();

        $secondary_title = $this->faker->sentence;

        $this->patch(route('posts.update',$post->slug),compact('secondary_title'));

        $this->assertEquals($secondary_title,Post::first()->secondary_title);
    }

    /**
     * @test
     */
    public function users_can_update_context_of_post()
    {
        $this->signIn()->ability('edit-news');

        $post = Post::factory()->create();

        $context = $this->faker->text();

        $this->patch(route('posts.update',$post->slug),compact('context'));

        $this->assertEquals($context,Post::first()->context);
    }

    /**
     * @test
     */
    public function users_can_update_image_of_post()
    {
        Storage::fake();

        $this->signIn()->ability('edit-news');

        $post = Post::factory()->released()->create();

        $image = UploadedFile::fake()->image('new-image.jpg');

        $this->patch(route('posts.update',$post->slug),compact('image'));

        $path = 'images/post/' . $image->hashName();

        $this->assertEquals($path,Post::first()->image);

        Storage::disk('public')->assertExists($path);

        Storage::disk('public')->assertMissing($post->image);
    }

    /**
     * @test
     */
    public function wrong_post_slug_must_return_not_found()
    {
        $this->signIn()->ability('edit-news');

        $post = Post::factory()->released()->create();

        $this->patch(route('posts.update','foobar'))->assertNotFound();
    }

    /**
     * @test
     */
    public function when_post_context_updated_previous_tags_should_not_exist_anymore()
    {
        Notification::fake();

        $this->signIn()->ability('edit-news');

        $post = Post::factory(['context' => '#Official: #Juventus signed with #Ronaldo.'])->released()->create();

        event(new DetectTagsEvent($post));

        $context = "Official: Juventus signed with Ronaldo.";

        $this->patch(route('posts.update',$post->slug),compact('context'));

        $this->assertDatabaseMissing('tags',[
            'name'  => 'Official',
            'count' => '1',
        ])->assertDatabaseMissing('tags',[
            'name' => 'Juventus',
            'count' => '1',
        ])->assertDatabaseMissing('tags',[
            'name'  => 'Ronaldo',
            'count' => '1',
        ]);
    }

    /**
     * @test
     */
    public function when_post_context_updated_new_tags_should_be_replaced()
    {
        $this->signIn()->ability('edit-news');

        $post = Post::factory(['context' => '#Official: #Juventus signed with #Ronaldo.'])->released()->create();

        $context = "#Unofficial: #RealMadrid signed with #Messi.";

        $this->patch(route('posts.update',$post->slug),compact('context'));

        $this->assertDatabaseHas('tags',[
            'name'  => 'Unofficial',
            'count' => '1',
        ])->assertDatabaseHas('tags',[
            'name' => 'RealMadrid',
            'count' => '1',
        ])->assertDatabaseHas('tags',[
            'name'  => 'Messi',
            'count' => '1',
        ]);
    }
}
