<?php

namespace App\Mail;

use App\Models\Customer;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Tasca;

class ReservationCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tasca $tasca;
    public Customer $customer;
    public Reservation $reservation;

    public function __construct(Tasca $tasca, Customer $customer, Reservation $reservation)
    {
        $this->tasca = $tasca;
        $this->customer = $customer;
        $this->reservation = $reservation;
        $this->onQueue('emails');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Created',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
