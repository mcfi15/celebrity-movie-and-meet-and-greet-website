<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $settings;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking->load(['celebrity', 'serviceType']);
        $this->settings = SiteSetting::getSetting();
    }

    public function build()
    {
        return $this->subject('Booking Update - ' . $this->booking->booking_number)
                    ->view('emails.booking-rejected')
                    ->with([
                        'booking' => $this->booking,
                        'settings' => $this->settings,
                    ]);
    }
}