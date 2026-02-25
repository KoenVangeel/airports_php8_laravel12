<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FlightScheduleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $firstname, $lastname, $carrier, $flightnumber, $etd, $eta;

    /**
     * Create a new message instance.
     */
    public function __construct($firstname, $lastname, $carrier, $flightnumber, $etd, $eta)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->carrier = $carrier;
        $this->flightnumber = $flightnumber;
        $this->etd = $etd;
        $this->eta = $eta;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Flight Schedule (Flight number: ' . $this->flightnumber . ') has been changed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.flightschedule',
            with: [
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'carrier' => $this->carrier,
                'flightnumber' => $this->flightnumber,
                'etd' => $this->etd,
                'eta' => $this->eta,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
