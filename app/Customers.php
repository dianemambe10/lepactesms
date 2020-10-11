<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Customers extends Model
{
    use HasApiTokens, Notifiable;


    protected $fillable = [
        'nom','prenom', 'email', 'telephone', 'password',
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];
}
