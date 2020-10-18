<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Volontaires extends Model
{
    //
    protected $table = 'pac_volontaire';
    protected $fillable = [
        'nom', 'prenom', 'email', 'ville', 'phone'
    ];
}
