<?php

namespace app\User\model;

use think\Model;

class User extends Model
{
    /**
     * 用户数据入库
     *
     * @param [type] $data  用户数据
     * @return void
     */
    public function userPut($data)
    {
        $data['ctime'] = time();
        $data['last_ip'] = $_SERVER['REMOTE_ADDR'];  //用户ip
        return $this->allowField(true)->save($data);
    }

    /**
     * 判断用户数据是否存在
     *
     * @param [type] $list  字段
     * @param [type] $data  判断的数据
     * @return void
     */
    public function dataExist($list, $data)
    {
        return $this->where($list, $data)->find();
    }

    /**
     * 更新数据
     *
     * @param [type] $id  用户id
     * @return void
     */
    public function updateLogin($id)
    {
        $updata['last_time'] = time();
        $updata['last_ip'] = $_SERVER['REMOTE_ADDR'];
        return $this->allowField('last_time', 'last_ip')->save($updata, ['id' => $id]);
    }
}
