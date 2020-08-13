<?php

namespace App\Model\Pages\Admin;

use App\Model\Pages\System\Route;
use Illuminate\Database\Eloquent\Model;

class AdmRole extends Model
{
    //
    protected $table = 'adm_role';//自定义表名（protected $table）
    protected $primaryKey = 'id';//主键字段，默认为id
    public $timestamps = false;//如果数据表中没有created_at和updated_id字段，则$timestamps则可以不设置，默认为true


    //关联权限模型
    public function route()
    {
        return $this->belongsToMany(Route::class, 'adm_role_route', 'role_id', 'route_id');
    }

}
