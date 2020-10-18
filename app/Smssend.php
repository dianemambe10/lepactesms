<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Smssend extends Model
{
    //
    protected $table = 'smssends';

    protected $fillable = [
        'user_id', 'sms_id', 'volontaire_id'
    ];
}
