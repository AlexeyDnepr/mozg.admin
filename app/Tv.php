<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tv extends Model {

    public $timestamps = false;

    public function tv_name() {
	return $this->belongsTo('App\Tv_name');
    }

}
