<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Coupon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNotificationCoupon extends Mailable
{
    use Queueable, SerializesModels;

    public User $client;
    public Coupon $coupon;

    public function __construct(User $client, Coupon $coupon)
    {
        $this->client = $client;
        $this->coupon = $coupon;
    }

    public function build()
    {
        return $this->subject('Admin: code promo fidélité attribué')
            ->view('emails.admin_coupon_notification');
    }
}
