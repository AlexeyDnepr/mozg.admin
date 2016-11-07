<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    //protected $table = 'pages';
    public function tvs()
    {
        return $this->hasMany('App\Tv');
    }
}
