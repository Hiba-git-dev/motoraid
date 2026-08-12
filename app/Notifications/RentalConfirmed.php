<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RentalConfirmed extends Notification
{
    use Queueable;

    protected $rental;

    public function __construct($rental)
    {
        $this->rental = $rental;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Location Confirmée 🏍️',
            'message' => 'Votre réservation pour la ' . $this->rental->motorcycle->brand . ' ' . $this->rental->motorcycle->model . ' est validée du ' . $this->rental->start_date . ' au ' . $this->rental->end_date . '.',
            'icon' => '✅',
            'url' => route('rentals.my')
        ];
    }
}