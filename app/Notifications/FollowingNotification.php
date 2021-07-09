<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class FollowingNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param string $type
     */
    public function __construct(public User $user,public string $type)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return [
            'broadcast',
            'database',
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @return BroadcastMessage
     */
    public function toBroadcast(): BroadcastMessage
    {
        return new BroadcastMessage([
            'url'     => route('users.show',$this->user->username),
            'image'   => $this->user->image,
            'message' => $this->message(),
        ]);
    }

    /**
     * Store in database representation of the notification.
     *
     * @return array
     */
    public function toDatabase(): array
    {
        return [
            'url'     => route('users.show',$this->user->username),
            'image'   => $this->user->image,
            'message' => $this->message(),
        ];
    }

    /**
     * @return string
     */
    private function message(): string
    {
        $messageType = [
            'follow'   => 'شما را دنبال می کند.',
            'unfollow' => 'دیگر شما را دنبال نمی کند.',
        ];
        return sprintf("%s %s",$this->user->name,$messageType[$this->type]);
    }
}
