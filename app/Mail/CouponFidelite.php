<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Coupon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CouponFidelite extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Coupon $coupon;

    public function __construct(User $user, Coupon $coupon)
    {
        $this->user = $user;
        $this->coupon = $coupon;
    }

    public function build()
    {
        return $this->subject('Votre code promo fidélité - Mini Amazon')
            ->view('emails.coupon_fidelite');
    }
}
