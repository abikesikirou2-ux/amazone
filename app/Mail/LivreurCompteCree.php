<?php

namespace App\Mail;

use App\Models\Livreur;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LivreurCompteCree extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Livreur $livreur;
    public ?string $plainPassword;

    public function __construct(Livreur $livreur, ?string $plainPassword = null)
    {
        $this->livreur = $livreur;
        $this->plainPassword = $plainPassword;
    }

    public function build(): self
    {
        return $this
            ->subject('Validation de votre compte livreur')
            ->view('emails.livreur.compte-cree')
            ->with([
                'livreur' => $this->livreur,
                'plainPassword' => $this->plainPassword,
            ]);
    }
}
