<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $contactMessage;
    public $settings;

    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
        $this->settings = SiteSetting::getSetting();
    }

    public function build()
    {
        return $this->subject('New Contact Message - ' . $this->contactMessage->subject)
                    ->view('emails.contact-message-received')
                    ->with([
                        'contactMessage' => $this->contactMessage,
                        'settings' => $this->settings,
                    ]);
    }
}