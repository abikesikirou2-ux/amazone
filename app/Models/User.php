<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use App\Mail\ClientCompteValidation;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relations
    public function panier()
    {
        return $this->hasOne(Panier::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function adressesLivraison()
    {
        return $this->hasMany(AdresseLivraison::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }

    // Méthodes helpers
    public function estAdmin()
    {
        return $this->role === 'admin';
    }

    public function estClient()
    {
        return $this->role === 'client';
    }

    /**
     * Envoi personnalisé de l'e-mail de vérification du compte client
     */
    public function sendEmailVerificationNotification(): void
    {
        // L'admin n'a pas besoin de vérification d'email
        if ($this->role === 'admin') {
            return;
        }
        // Génère une URL signée temporaire pour la vérification
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification()),
            ]
        );

        try {
            $mailable = new ClientCompteValidation($this, $verificationUrl);
            // Envoi synchronisé ou via queue selon la config
            if (config('queue.default') === 'sync') {
                Mail::to($this->email)->send($mailable);
            } else {
                Mail::to($this->email)->queue($mailable);
            }
        } catch (\Throwable $e) {
            \Log::warning('Email verification (client) non envoyé: ' . $e->getMessage());
        }
    }
}
