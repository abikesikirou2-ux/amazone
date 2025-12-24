<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InscriptionNewsletter extends Model
{
    protected $table = 'inscriptions_newsletter';

    protected $fillable = [
        'email',
    ];
}
