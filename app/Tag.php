<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function members() {
        return $this->hasMany('App\Friend');
    }
}
