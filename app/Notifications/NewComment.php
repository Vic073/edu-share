<?php
// app/Notifications/NewComment.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Material;
use App\Models\Comment;
use App\Models\User;

class NewComment extends Notification implements ShouldQueue
{
    use Queueable;

    public $material;
    public $comment;
    public $commenter;

    public function __construct(Material $material, Comment $comment, User $commenter)
    {
        $this->material = $material;
        $this->comment = $comment;
        $this->commenter = $commenter;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'material_id' => $this->material->id,
            'material_title' => $this->material->title,
            'comment' => $this->comment->comment,
            'user_id' => $this->commenter->id,
            'user_name' => $this->commenter->name,
        ];
    }
}