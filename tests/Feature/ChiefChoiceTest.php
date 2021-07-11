<?php

namespace Tests\Feature;

use App\Models\ChiefChoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChiefChoiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guest_users_cannot_see_this()
    {
        ChiefChoice::factory()->count(5)->create();

        $this->get(route('chief-choices.index'))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_users_cannot_see_this()
    {
        $this->signIn();

        ChiefChoice::factory()->count(5)->create();

        $this->get(route('chief-choices.index'))->assertForbidden();
    }

    /**
     * @test
     */
    public function it_could_be_shown()
    {
        $this->signIn()->ability('manage-chief-choice');

        ChiefChoice::factory()->count(5)->create();

        $this->get(route('chief-choices.index'))->assertOk()->assertJsonStructure([
            [
                'id',
                'slug',
                'title',
                'image',
            ],
        ])->assertJsonCount(5);
    }

    /**
     * @test
     */
    public function guest_users_cannot_store_this()
    {
        $chiefChoice = ChiefChoice::factory()->raw();

        $this->post(route('chief-choices.store'), $chiefChoice)->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_users_cannot_store_this()
    {
        $this->signIn();

        $chiefChoice = ChiefChoice::factory()->raw();

        $this->post(route('chief-choices.store'), $chiefChoice)->assertForbidden();
    }

    /**
     * @test
     */
    public function it_require_post_id()
    {
        $this->signIn()->ability('manage-chief-choice');

        $chiefChoice = ChiefChoice::factory(['post_id' => ''])->raw();

        $this->post(route('chief-choices.store'), $chiefChoice)->assertSessionHasErrors('post_id');
    }

    /**
     * @test
     */
    public function it_could_be_stored()
    {
        $this->signIn()->ability('manage-chief-choice');

        $chiefChoice = ChiefChoice::factory()->raw();

        $this->post(route('chief-choices.store'), $chiefChoice)->assertCreated();
    }

    /**
     * @test
     */
    public function it_must_return_proper_json_after_storing()
    {
        $this->signIn()->ability('manage-chief-choice');

        $chiefChoice = ChiefChoice::factory()->raw();

        $this->post(route('chief-choices.store'), $chiefChoice)->assertJsonStructure([
            'message',
        ]);
    }

    /**
     * @test
     */
    public function delete_item_must_be_exists_in_database()
    {
        $this->signIn()->ability('manage-chief-choice');

        ChiefChoice::factory()->create();

        $chiefChoice = ChiefChoice::factory()->raw();

        $chiefChoice['delete_item'] = 2;

        $this->post(route('chief-choices.store'), $chiefChoice)->assertSessionHasErrors('delete_item');
    }

    /**
     * @test
     */
    public function it_could_be_delete_item_if_passed_item_id_when_storing()
    {
        $this->signIn()->ability('manage-chief-choice');

        $chiefChoice = ChiefChoice::factory()->create();

        $newChiefChoice = ChiefChoice::factory()->raw();

        $newChiefChoice['delete_item'] = $chiefChoice->id;

        $this->assertDatabaseHas('chief_choices', $chiefChoice->only([
            'post_id',
            'order',
        ]));

        $this->post(route('chief-choices.store'), $newChiefChoice);

        $this->assertDatabaseMissing('chief_choices', $chiefChoice->only([
            'post_id',
            'order',
        ]));
    }
}
