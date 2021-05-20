<?php

namespace App\Listeners;

use App\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateTagListener
{
    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle($event)
    {
        try {
            DB::beginTransaction();

            $foundTag = Tag::where('name', $event->name)
                           ->first();

            if (is_null($foundTag)) {
                $createdTag = Tag::create([
                    'name'  => $event->name,
                    'count' => 1,
                ]);
                $createdTag->taggables()
                           ->create([
                               'taggable_id'   => $event->id,
                               'taggable_type' => $event->type,
                           ]);
            } else {
                $foundTag->increment('count');
                $foundTag->taggables()
                         ->create([
                             'taggable_id'   => $event->id,
                             'taggable_type' => $event->type,
                         ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('create tag fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile());
        }
    }
}
