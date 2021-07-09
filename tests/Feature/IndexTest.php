<?php

namespace Tests\Feature;

use App\Models\BroadcastSchedule;
use App\Models\ChiefChoice;
use App\Models\Competition;
use App\Models\Fixture;
use App\Models\Post;
use App\Models\Scorers;
use App\Models\Slider;
use App\Models\Standing;
use App\Models\Survey;
use App\Models\Tag;
use App\Models\User;
use App\Models\Vote;
use Database\Seeders\TagsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TagsSeeder::class);
    }

    /**
     * @test
     */
    public function trend_tags_must_be_passed_to_index_page()
    {

        Tag::factory(['count' => 1])->hasAttached(Post::factory())->count(20)->create();

        $response = $this->get(route('index'))->assertViewHas([
            'response.trends',
        ]);

        $this->assertCount(10,$response['response']['trends']);
    }

    /**
     * @test
     */
    public function trend_tags_must_be_in_order()
    {
        Tag::factory([
            'name'  => 'ronaldo',
            'count' => 2,
        ])->hasAttached(Post::factory()->count(2))->create();

        Tag::factory([
            'name'  => 'messi',
            'count' => 3,
        ])->hasAttached(Post::factory()->count(3))->create();

        $this->get(route('index'));

        $this->get(route('index'))->assertViewHas([
            'response.trends.0' => [
                'name'  => "#messi",
                'count' => 3,
                'url'   => route('tags.show','messi'),
            ],
            'response.trends.1' => [
                'name'  => "#ronaldo",
                'count' => 2,
                'url'   => route('tags.show','ronaldo'),
            ],
        ]);
    }

    /**
     * @test
     */
    public function slider_must_be_passed_to_index_page()
    {
        Slider::factory()->count(10)->create();

        $this->get(route('index'))->assertViewHas([
            'response.slider.0.title',
            'response.slider.0.mainTitle',
            'response.slider.0.secondaryTitle',
            'response.slider.0.firstTag',
            'response.slider.0.firstTagURL',
            'response.slider.0.secondTag',
            'response.slider.0.secondTagURL',
            'response.slider.0.thirdTag',
            'response.slider.0.thirdTagURL',
            'response.slider.0.forthTag',
            'response.slider.0.forthTagURL',
            'response.slider.0.image',
            'response.slider.0.newsURL',
        ]);
    }

    /**
     * @test
     */
    public function last_news_must_be_passed_to_index_page()
    {
        Post::factory()->released()->count(30)->create();

        $response = $this->get(route('index'))->assertViewHas([
            'response.lastNews.0.title',
            'response.lastNews.0.time',
            'response.lastNews.0.url',
        ]);

        $this->assertCount(15,$response['response']['lastNews']);
    }

    /**
     * @test
     */
    public function most_followed_users_must_be_passed_to_index_page()
    {
        User::factory()->count(10)->hasFollowers(rand(1,5))->create();

        $response = $this->get(route('index'))->assertViewHas([
            'response.mostFollowedUsers.0.name',
            'response.mostFollowedUsers.0.countFollowers',
            'response.mostFollowedUsers.0.countFollowings',
            'response.mostFollowedUsers.0.countPosts',
            'response.mostFollowedUsers.0.image',
            'response.mostFollowedUsers.0.url',
        ]);

        $this->assertCount(5,$response['response']['mostFollowedUsers']);
    }

    /**
     * @test
     */
    public function competition_news_must_be_passed_to_index_page()
    {
        $tag = Tag::whereName('خلیج_فارس')->first();

        Post::factory()->count(20)->news()->released()->hasAttached($tag)->create();

        $response = $this->get(route('index'))->assertViewHas([
            'response.competitionNews.0.title',
            'response.competitionNews.0.time',
            'response.competitionNews.0.url',
        ]);

        $this->assertCount(15,$response['response']['competitionNews']);

    }

    /**
     * @test
     */
    public function competition_news_must_be_in_order()
    {
        $this->travelTo(now()->subDay());

        $tag = Tag::whereName('خلیج_فارس')->first();

        $news1 = Post::factory()->news()->released()->create();

        $news1->tags()->attach($tag);

        $this->travelTo(now()->addDay());

        $news2 = Post::factory()->news()->released()->create();

        $news2->tags()->attach($tag);

        $response = $this->get(route('index'));

        $this->assertEquals($response['response']['competitionNews'][0]['title'],$news2->title);

        $this->assertEquals($response['response']['competitionNews'][1]['title'],$news1->title);
    }

    /**
     * @test
     */
    public function hot_news_must_be_passed_to_index_page()
    {
        Post::factory()->released()->count(30)->create();

        $response = $this->get(route('index'))->assertViewHas([
            'response.hotNews.0.title',
            'response.hotNews.0.time',
            'response.hotNews.0.url',
        ]);

        $this->assertCount(15,$response['response']['hotNews']);
    }

    /**
     * @test
     */
    public function chief_choices_must_be_passed_to_index_page()
    {
        ChiefChoice::factory()->count(10)->create();

        $this->get(route('index'))->assertViewHas([
            'response.chiefChoices.0.title',
            'response.chiefChoices.0.image',
            'response.chiefChoices.0.url',
        ]);
    }

    /**
     * @test
     */
    public function surveys_must_be_passed_to_index_page()
    {
        Survey::factory()->create();

        $this->get(route('index'))->assertViewHas([
            'response.survey.id',
            'response.survey.question',
            'response.survey.options.0.title',
            'response.survey.options.0.count',
            'response.surveySelectedOption',
        ]);
    }

    /**
     * @test
     */
    public function if_user_voted_survey_selected_option_must_be_pass()
    {
        $user = User::factory()->has(Vote::factory(['option' => 2]))->create();

        $this->signIn($user);

        $this->get(route('index'))->assertViewHas([
            'response.surveySelectedOption' => 2,
        ]);
    }

    /**
     * @test
     */
    public function broadcast_schedule_must_be_passed_to_index_page()
    {
        BroadcastSchedule::factory()->count(5)->create();

        $this->get(route('index'))->assertViewHas([
            'response.broadcastSchedule.0.id',
            'response.broadcastSchedule.0.host',
            'response.broadcastSchedule.0.guest',
            'response.broadcastSchedule.0.datetime',
            'response.broadcastSchedule.0.time',
            'response.broadcastSchedule.0.image',
            'response.broadcastSchedule.0.alt',
        ]);
    }

    /**
     * @test
     */
    public function competition_news_must_be_accessible_in_index_page()
    {
        Post::factory()->has(Tag::factory(['name' => 'فولاد']),'tags')->create();

        $this->get(route('index.competition-news',[
            'tag' => 'فولاد',
        ]))->assertOk();
    }
}
