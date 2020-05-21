<?php
namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    /**
     * 判断账号是否存在
     *
     * @param [type] $name  账号
     * @return void
     */
    public function userExist($name)
    {
        return $this->where('name', $name)->find();
    }

    /**
     * 管理员添加
     *
     * @param [type] $data  数据
     * @return void
     */
    public function addAdmins($data)
    {
        $data['ctime'] = time();
        return $this->allowField(true)->save($data);
    }

    /**
     * 最后登录数据更新
     *
     * @param [type] $id
     * @return void
     */
    public function updateLastTime($id)
    {
        $data['last_ip'] = $_SERVER['REMOTE_ADDR'];  //更新ip
        $data['last_time'] = time();
        $this->save($data,['id'=>$id]);
    }
}