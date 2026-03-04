<?php

// app/Notifications/NewDownload.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Material;
use App\Models\User;

class NewDownload extends Notification implements ShouldQueue
{
    use Queueable;

    public $material;
    public $downloader;

    public function __construct(Material $material, User $downloader)
    {
        $this->material = $material;
        $this->downloader = $downloader;
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
            'user_id' => $this->downloader->id,
            'user_name' => $this->downloader->name,
        ];
    }
}
