<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\RegistrationMail;
use Illuminate\Support\Facades\Mail;

class SendRegistrationEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        Mail::to($event->user)->send(new RegistrationMail($event->user));
    }
}
