<?php

namespace App\Mail;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminCommandeLancee extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Commande $commande;

    public function __construct(Commande $commande)
    {
        $this->commande = $commande;
    }

    public function build(): self
    {
        return $this
            ->subject('Mini Amazon — Nouvelle commande lancée')
            ->view('emails.admin.commande-lancee')
            ->with([
                'commande' => $this->commande,
            ]);
    }
}
