<?php

namespace App\Mail;

use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Tasca;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationCancelledMail extends Mailable
{
    use Queueable;

    public $tasca;
    public $customer;
    public $reservation;

    public function __construct(array $tascaData, array $customerData, array $reservationData)
    {
        $this->tasca = $tascaData;
        $this->customer = $customerData;
        $this->reservation = $reservationData;
        $this->onQueue('emails');
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Cancelled',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-cancelled',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
