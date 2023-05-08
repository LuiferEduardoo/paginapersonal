<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $content;
    public $subjectInput;
    public $date;
    public $settled; 

    public function __construct($name, $email, $content, $subjectInput, $settled)
    {
        $this->name = $name; 
        $this->email = $email; 
        $this->content = $content;
        $this->subjectInput = $subjectInput;
        $this->settled = $settled;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Respuesta automatica a tu mensaje: $this->subjectInput",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'notification',
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
