<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PostLikedNotification extends Notification
{
    use Queueable;

    protected $liker;
    protected $post;

    public function __construct(Post $post, User $liker)
    {
        $this->post = $post;
        $this->liker = $liker;
    }


    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function todatabase($notifiable)
    {
        return [
            'post_id' => $this->post->id,
            'liker_id' => $this->liker->id,
            'message' => "{$this->liker->name} liked your post.",

        ];
    }
}
