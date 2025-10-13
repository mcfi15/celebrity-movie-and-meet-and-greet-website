<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageReply extends Mailable
{
    use Queueable, SerializesModels;

    public $contactMessage;
    public $replyMessage;
    public $settings;

    public function __construct(ContactMessage $contactMessage, $replyMessage)
    {
        $this->contactMessage = $contactMessage;
        $this->replyMessage = $replyMessage;
        $this->settings = SiteSetting::getSetting();
    }

    public function build()
    {
        return $this->subject('Re: ' . $this->contactMessage->subject)
                    ->view('emails.contact-message-reply')
                    ->with([
                        'contactMessage' => $this->contactMessage,
                        'replyMessage' => $this->replyMessage,
                        'settings' => $this->settings,
                    ]);
    }
}