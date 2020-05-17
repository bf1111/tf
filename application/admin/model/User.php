<?php

namespace app\admin\model;

use think\Model;

class User extends Model
{

    protected $autoWriteTimestamp = true;
    /**
     * 添加用户入库操作
     *
     * @param [type] $data  数据
     * @return void
     */
    public function addUsers($data)
    {
        $data['ctime'] = time();
        $data["last_ip"] = $_SERVER['REMOTE_ADDR'];  //用户ip
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
        $res = $this->where($list, $data)->find();
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 用户信息
     *
     * @param integer $number  每页显示的条数
     * @return void
     */
    public function getUsers($number = 25)
    {
        $data = [
            "list_status" => 1
        ];
        $order = [
            "id" => "desc"
        ];
        return $this->where($data)->order($order)->paginate($number);
    }

    /**
     * 获取指定用户信息
     *
     * @param [type] $id 用户id
     * @return void
     */
    public function getOneUser($id)
    {
        return $this->where(['id' => $id])->find();
    }

    /**
     * 用户信息更新
     *
     * @param [type] $data  更新的数据
     * @param [type] $id    更新用户的id
     * @return void
     */
    public function updateUser($data, $id)
    {
        $data['edit_time'] = time();
        return $this->save($data, ['id' => $id]);
    }

    public function userDelete($id)
    {
        return $this->save(['list_status' => -1], ['id' => $id]);
    }

    public function searchUser($searchType, $search)
    {
        //通过账号搜索
        if ($searchType == 1) {
            return $this->where(['name' => $search, 'list_status' => 1])->paginate(25);
        }

        //通过昵称搜索
        if ($searchType == 2) {
            return $this->where(['username' => $search, 'list_status' => 1])->paginate(25);
        }

        //通过手机号搜索
        if($searchType == 3){
            return $this->where(['phone' => $search, 'list_status' => 1])->paginate(25);
        }

        //通过邮箱搜索
        if($searchType == 4){
            return $this->where(['email' => $search, 'list_status' => 1])->paginate(25);
        }
    }
}
