<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteTagListener
{
    /**
     * Handle the event.
     *
     * @param  DeleteTag  $event
     * @return void
     */
    public function handle($event)
    {
        try{
            DB::beginTransaction();
            $tags = DB::table('taggables')
                      ->where('taggables.taggable_id', '=', $event->meta_id)
                      ->where('taggables.taggable_type', '=', $event->meta_type)
                      ->get();
            foreach ($tags as $tag) {
                DB::table('tags')
                  ->where('tags.id', '=', $tag->tag_id)
                  ->decrement('count');
            }
            DB::table('taggables')
              ->where('taggables.taggable_id', '=', $event->meta_id)
              ->where('taggables.taggable_type', '=', $event->meta_type)
              ->delete();
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            $message = "delete tags fails because of: {$e->getMessage()} on line : {$e->getLine()} in file: {$e->getFile()}";
            Log::error($message);
            abort(500,$message);
        }

    }
}
