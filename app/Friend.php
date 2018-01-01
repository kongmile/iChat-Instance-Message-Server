<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    public function tag() {
        return $this->belongsTo('App\Tag');
    }

    public function friend() {
        return $this->belongsTo('App\User', 'user2');
    }
}
