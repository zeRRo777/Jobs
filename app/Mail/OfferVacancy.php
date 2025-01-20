<?php

namespace App\Mail;

use App\Models\Vacancy;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OfferVacancy extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $vacancy;
    public $user;
    public  $admin;

    /**
     * Create a new message instance.
     */
    public function __construct(Vacancy $vacancy, User $user, User $admin)
    {
        $this->vacancy = $vacancy;
        $this->user = $user;
        $this->admin = $admin;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Offer Job',
            from: new Address($this->admin->email, $this->admin->name)
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.offer',
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
