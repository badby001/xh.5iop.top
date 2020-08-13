<?php

namespace App\Model\Pages\Admin;

use Illuminate\Database\Eloquent\Model;

class AdmUserInfo extends Model
{
    //
    protected $table = 'adm_user_info';
    protected $primaryKey = 'pub_user_id';//主键字段，默认为id
    public $timestamps = false;
    //const CREATED_AT = 'add_time';
}
