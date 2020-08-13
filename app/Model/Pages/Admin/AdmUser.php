<?php

namespace App\Model\Pages\Admin;

use Illuminate\Database\Eloquent\Model;

class AdmUser extends Model
{
    //
    protected $table = 'adm_user';//自定义表名（protected $table）
    protected $primaryKey = 'id';//主键字段，默认为id
    public $timestamps = false;//如果数据表中没有created_at和updated_id字段，则$timestamps则可以不设置，默认为true

    public function admUserInfo()
    {
        return $this->hasOne(AdmUserInfo::class, 'adm_code', 'code');
    }


    public function admUserRole()
    {//根据用户查找所有用户角色
        return $this->belongsToMany(AdmRole::class, 'adm_user_role', 'adm_id', 'role_id');
    }

}
