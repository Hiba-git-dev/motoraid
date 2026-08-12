<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SosAlert extends Notification
{
    use Queueable;

    protected $user;
    protected $mapsLink;

    public function __construct($user, $mapsLink)
    {
        $this->user = $user;
        $this->mapsLink = $mapsLink;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => '🚨 ALERTE SOS',
            'message' => $this->user->name . ' a déclenché une alerte d\'urgence. Cliquez pour localiser.',
            'icon' => '🆘',
            'url' => $this->mapsLink
        ];
    }
}