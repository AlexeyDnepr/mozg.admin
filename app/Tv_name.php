<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tv_name extends Model {

    protected $table = 'tv_names';

    public function tvs() {
	return $this->hasMany('App\Tv');
    }

}
