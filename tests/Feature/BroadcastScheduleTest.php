<?php

namespace Tests\Feature;

use App\Models\BroadcastSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BroadcastScheduleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guest_users_cannot_store_broadcast_schedule()
    {
        $schedule = BroadcastSchedule::factory()->raw();

        $this->post(route('broadcast-schedules.store'), $schedule)->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_users_cannot_store_broadcast_schedule()
    {
        $this->signIn();

        $schedule = BroadcastSchedule::factory()->raw();

        $this->post(route('broadcast-schedules.store'), $schedule)->assertForbidden();
    }

    /**
     * @test
     */
    public function it_requires_host()
    {
        $this->signIn()->ability('manage-broadcast-schedule');

        $schedule = BroadcastSchedule::factory(['host' => ''])->raw();

        $this->post(route('broadcast-schedules.store'), $schedule)->assertSessionHasErrors('host');
    }

    /**
     * @test
     */
    public function it_requires_guest()
    {
        $this->signIn()->ability('manage-broadcast-schedule');

        $schedule = BroadcastSchedule::factory(['guest' => ''])->raw();

        $this->post(route('broadcast-schedules.store'), $schedule)->assertSessionHasErrors('guest');
    }

    /**
     * @test
     */
    public function it_requires_broadcast_channel_id()
    {
        $this->signIn()->ability('manage-broadcast-schedule');

        $schedule = BroadcastSchedule::factory(['broadcast_channel_id' => ''])->raw();

        $this->post(route('broadcast-schedules.store'), $schedule)->assertSessionHasErrors('broadcast_channel_id');
    }

    /**
     * @test
     */
    public function it_requires_broadcast_channel_exist_in_database()
    {
        $this->signIn()->ability('manage-broadcast-schedule');

        $schedule = BroadcastSchedule::factory(['broadcast_channel_id' => 2])->raw();

        $this->post(route('broadcast-schedules.store'), $schedule)->assertSessionHasErrors('broadcast_channel_id');
    }

    /**
     * @test
     */
    public function it_requires_datetime()
    {
        $this->signIn()->ability('manage-broadcast-schedule');

        $schedule = BroadcastSchedule::factory(['datetime' => ''])->raw();

        $this->post(route('broadcast-schedules.store'), $schedule)->assertSessionHasErrors('datetime');
    }

    /**
     * @test
     */
    public function it_could_store()
    {
        $this->signIn()->ability('manage-broadcast-schedule');

        $schedule = BroadcastSchedule::factory()->raw();

        $schedule['datetime'] = $schedule['datetime']->getTimestamp();

        $this->post(route('broadcast-schedules.store'), $schedule)->assertCreated();

        $this->assertDatabaseHas('broadcast_schedule', $schedule);
    }

    /**
     * @test
     */
    public function it_must_return_proper_json_after_storing()
    {
        $this->signIn()->ability('manage-broadcast-schedule');

        $schedule = BroadcastSchedule::factory()->raw();

        $schedule['datetime'] = $schedule['datetime']->getTimestamp();

        $this->post(route('broadcast-schedules.store'), $schedule)->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'host',
                'guest',
                'datetime',
                'time',
                'image',
                'alt',
            ],
        ]);
    }

    /**
     * @test
     */
    public function guest_users_cannot_update_broadcast_schedule()
    {
        $schedule = BroadcastSchedule::factory()->create();

        $newSchedule = BroadcastSchedule::factory()->raw();

        $this->put(route('broadcast-schedules.update', $schedule->id), $newSchedule)->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_users_cannot_update_broadcast_schedule()
    {
        $this->signIn();

        $schedule = BroadcastSchedule::factory()->create();

        $newSchedule = BroadcastSchedule::factory()->raw();

        $this->put(route('broadcast-schedules.update', $schedule->id), $newSchedule)->assertForbidden();
    }

    /**
     * @test
     */
    public function it_could_update()
    {
        $this->signIn()->ability('manage-broadcast-schedule');

        $schedule = BroadcastSchedule::factory()->create();

        $newSchedule = BroadcastSchedule::factory()->raw();

        $newSchedule['datetime'] = $newSchedule['datetime']->getTimestamp();

        $this->put(route('broadcast-schedules.update', $schedule->id), $newSchedule)->assertOk();

        $this->assertDatabaseHas('broadcast_schedule', $newSchedule);
    }

    /**
     * @test
     */
    public function it_must_return_proper_json_after_updating()
    {
        $this->signIn()->ability('manage-broadcast-schedule');

        $schedule = BroadcastSchedule::factory()->create();

        $newSchedule = BroadcastSchedule::factory()->raw();

        $newSchedule['datetime'] = $newSchedule['datetime']->getTimestamp();

        $this->put(route('broadcast-schedules.update', $schedule->id), $newSchedule)->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'host',
                'guest',
                'datetime',
                'time',
                'image',
                'alt',
            ],
        ]);
    }

    /**
     * @test
     */
    public function guest_users_cannot_destroy_broadcast_schedule()
    {
        $schedule = BroadcastSchedule::factory()->create();

        $this->delete(route('broadcast-schedules.destroy', $schedule->id))->assertRedirect('login');
    }

    /**
     * @test
     */
    public function unauthorized_users_cannot_destroy_broadcast_schedule()
    {
        $this->signIn();

        $schedule = BroadcastSchedule::factory()->create();

        $this->delete(route('broadcast-schedules.destroy', $schedule->id))->assertForbidden();
    }

    /**
     * @test
     */
    public function it_could_destroy()
    {
        $this->signIn()->ability('manage-broadcast-schedule');

        $schedule = BroadcastSchedule::factory()->create();

        $this->assertDatabaseHas('broadcast_schedule', $schedule->only([
            'host',
            'guest',
            'broadcast_channel_id',
            'datetime',
        ]));

        $this->delete(route('broadcast-schedules.destroy', $schedule->id))->assertOk();

        $this->assertDatabaseMissing('broadcast_schedule', $schedule->only([
            'host',
            'guest',
            'broadcast_channel_id',
            'datetime',
        ]));
    }

    /**
     * @test
     */
    public function it_must_return_proper_json_after_deleting()
    {
        $this->signIn()->ability('manage-broadcast-schedule');

        $schedule = BroadcastSchedule::factory()->create();

        $this->delete(route('broadcast-schedules.destroy', $schedule->id))->assertJsonStructure([
            'message',
        ]);
    }
}
