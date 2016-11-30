<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVirtualAccounts extends Model {

    protected $table = 'user_accounts_virtual';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

}
