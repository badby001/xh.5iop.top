<?php

namespace App\Model\Pages\System;

use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model
{
    //
    protected $table = 'pub_site_config';//自定义表名（protected $table）
    protected $primaryKey = 'id';//主键字段，默认为id
    public $timestamps = false;//如果数据表中没有created_at和updated_id字段，则$timestamps则可以不设置，默认为true
}
