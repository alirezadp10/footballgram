<?php

namespace App\Listeners;

use App\Models\Tag;

class DetectTagsListener
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $this->persistTag($event);

        $this->updateContext($event);
    }

    private function persistTag($event)
    {
        preg_match_all("/(\B#)(\w+)/u",$event->contextable()->context,$tags);

        $event->contextable()->tags()->delete();

        foreach ($tags[2] as $tag) {
            $event->contextable()->tags()->save($tag = Tag::firstOrNew(['name' => $tag]));
            $tag->increment('count');
        }
    }

    private function updateContext($event)
    {
        $context = preg_replace("/(\B#)(\w+)/u","<a href='/tags/$2' class='hashtag'>$0</a>",$event->contextable()->context);

        $event->contextable()->update([
            'context' => $context,
        ]);
    }
}
