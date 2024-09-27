<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewFollowerNotification extends Notification
{
    use Queueable;

    protected $followerId;

    public function __construct(User $follower)
    {
        $this->followerId = $follower->id;    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'follower_id' => $this->followerId,
            'message' => "A new user started following you.",
        ];
    }

}
