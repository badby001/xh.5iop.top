<?php

namespace App\Model\Pages\Admin;

use Illuminate\Database\Eloquent\Model;

class AdmGroup extends Model
{
    //
    protected $table = 'adm_group';//自定义表名（protected $table）
//    protected $primaryKey = 'adm_code';//主键字段，默认为id
    public $timestamps = false;//如果数据表中没有created_at和updated_id字段，则$timestamps则可以不设置，默认为true
}
