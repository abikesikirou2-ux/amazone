<?php

namespace App\Mail;

use App\Models\Commande;
use App\Models\Livreur;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemandeDisponibiliteLivreur extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Commande $commande;
    public Livreur $livreur;
    public string $acceptUrl;
    public string $refuseUrl;

    public function __construct(Commande $commande, Livreur $livreur, string $acceptUrl, string $refuseUrl)
    {
        $this->commande = $commande;
        $this->livreur = $livreur;
        $this->acceptUrl = $acceptUrl;
        $this->refuseUrl = $refuseUrl;
    }

    public function build(): self
    {
        return $this
            ->subject('Nouvelle commande à livrer — Confirmez votre disponibilité')
            ->view('emails.livreur.demande-disponibilite')
            ->with([
                'commande' => $this->commande,
                'livreur' => $this->livreur,
                'acceptUrl' => $this->acceptUrl,
                'refuseUrl' => $this->refuseUrl,
            ]);
    }
}
