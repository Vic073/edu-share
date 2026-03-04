<?php

// app/Notifications/NewRating.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Material;
use App\Models\Rating;
use App\Models\User;

class NewRating extends Notification implements ShouldQueue
{
    use Queueable;

    public $material;
    public $rating;
    public $rater;

    public function __construct(Material $material, Rating $rating, User $rater)
    {
        $this->material = $material;
        $this->rating = $rating;
        $this->rater = $rater;
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
            'rating' => $this->rating->rating,
            'user_id' => $this->rater->id,
            'user_name' => $this->rater->name,
        ];
    }
}