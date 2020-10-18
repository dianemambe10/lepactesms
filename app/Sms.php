<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    //
    protected $table = 'sms';

    protected $fillable=
[
   'title', 'content', 'user_id'
];

public function user(){
    return $this->belongsTo(User::class);
}

}
