<?php

namespace App\Mail;

use App\Models\Reservation;
use App\Models\Tasca;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationTascaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tasca $tasca;
    public User $user;
    public Reservation $reservation;

    public function __construct(User $user, Reservation $reservation, Tasca $tasca)
    {
        $this->tasca = $tasca;
        $this->reservation = $reservation;
        $this->user = $user;
        $this->onQueue('emails');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Tasca',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-tasca',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
