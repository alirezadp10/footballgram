<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\MentionNotification;

class DetectMentionsListener
{
    private $detectedUsers = [];

    /**
     * Handle the event.
     *
     * @param object $event
     *
     * @return void
     */
    public function handle($event)
    {
        $event->contextable()->update([
            'context' => $this->convertContext($event->contextable()->context),
        ]);

        array_map(
            fn ($user) => $user->notify(new MentionNotification($event->contextable())),
            $this->detectedUsers
        );
    }

    /**
     * @param string $context
     *
     * @return string
     */
    private function convertContext(string $context): string
    {
        return preg_replace_callback("/(\B@)(\w+)/u", [$this, 'checkExist'], $context);
    }

    private function checkExist($mention)
    {
        [$original,,$username] = $mention;

        $user = User::whereUsername($username)->first();

        if ($user == null) {
            return $original;
        }

        $this->detectedUsers[] = $user;

        return "<a href='/users/{$username}' class='mention'>{$original}</a>";
    }
}
