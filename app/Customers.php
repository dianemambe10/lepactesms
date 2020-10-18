<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Customers extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'customers';
    protected $fillable = [
        'nom','prenom', 'email', 'telephone', 'password',
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];
}
