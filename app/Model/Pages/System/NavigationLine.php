<?php

namespace App\Model\Pages\System;

use App\Model\Pages\XQERPV3\BaseLine;
use Illuminate\Database\Eloquent\Model;

class NavigationLine extends Model
{
    //
    protected $table = 'pub_navigation_line';//自定义表名（protected $table）
    protected $primaryKey = 'id';//主键字段，默认为id
    public $timestamps = false;//如果数据表中没有created_at和updated_id字段，则$timestamps则可以不设置，默认为true

    //线路类别
    public function basesLine()
    {
        return $this->hasOne(BaseLine::class, 'id', 'line_id');
    }

}
