<?php

namespace app\admin\controller;

use think\Controller;

class User extends Controller
{
    protected $obj;

    public function initialize()
    {
        // session('token','111','admin');
        if(!session('token','','admin')){
            // echo show(10,'请登录');
            echo "空";
            exit;
        }else{
            echo session('token','','admin');
            exit;
        }
        //User模板
        $this->obj = model("User");
    }
    
    /**
     * 添加用户操作
     *
     * @return void
     */
    public function usersAdd()
    {
        //判断数据是否Post提交
        requestPost();

        //接收数据
        $data = input('post.');

        //验证器，验证数据
        $validate = validate('User');
        if (!$validate->scene('add')->check($data)) {
            echo show("2", $validate->getError());
            exit;
        }

        //判断用户名是否存在
        if ($this->obj->dataExist('name', $data['name'])) {
            echo show('2', '该用户名已存在');
            exit;
        }

        //判断手机号是否已被注册
        if ($this->obj->dataExist('phone', $data['phone'])) {
            echo show('2', '该手机号已被注册');
            exit;
        }

        //判断邮箱是否已被注册
        if ($this->obj->dataExist('email', $data['email'])) {
            echo show('2', '该邮箱已被注册');
            exit;
        }

        //判断两次密码是否一致
        if ($data['password'] != $data['repassword']) {
            echo show("2", "您输入的两次密码不一致");
            exit;
        }

        //自动生成  加盐字符串
        $data['code'] = mt_rand(100, 10000);
        $data['password'] = md5($data['password'] . $data['code']);  //密码加密
        $data['username'] = $data['name'];   //默认用户昵称为用户账号

        //数据入库
        $res = $this->obj->addUsers($data);
        if ($res) {
            echo show(0, '用户添加成功');
        } else {
            echo show(2, "用户添加失败");
        }
    }

    /**
     * 用户列表显示
     *
     * @return void
     */
    public function userList()
    {

        $data = $this->obj->getUsers();
        echo show(0, "", $data);
    }

    /**
     * 用户编辑(用户数据显示)
     *
     * @return void
     */
    public function getEditUser()
    {
        //判断请求类型
        requestGet();

        //接收数据
        $userId = input('get.id');

        if ($userId) {
            //数据库  查询用户信息
            $data = $this->obj->getOneUser($userId);
            echo show(0, "", $data);
        } else {
            echo show(2, "数据错误");
        }
    }

    /**
     * 用户编辑(用户数据更新)
     *
     * @return void
     */
    public function userEditPost()
    {
        //判断请求类型是否post提交
        requestPost();

        //接收数据
        $data = input('post.');

        //验证器 验证数据
        $validate = validate('User');
        if (!$validate->scene('edit')->check($data)) {
            echo show("2", $validate->getError());
            exit;
        }

        //判断图片是否上传
        // if(!$data['file']){
        //    echo show(2,'请上传图片'); 
        // }

        //判断手机号是否已被注册
        if ($this->obj->userEditExist('phone', $data['phone'],$data['id'])) {
            echo show('2', '该手机号已被注册');
            exit;
        }

        //判断邮箱是否已被注册
        if ($this->obj->userEditExist('email', $data['email'],$data['id'])) {
            echo show('2', '该邮箱已被注册');
            exit;
        }

        //自动生成  加盐字符串
        $data['code'] = mt_rand(100, 10000);
        $data['password'] = md5($data['password'] . $data['code']);  //密码加密

        //更新数据
        $res = $this->obj->updateUser($data, $data['id']);
        if ($res) {
            echo show(0, '编辑成功');
        } else {
            echo show(2, '编辑失败');
        }
    }

    /**
     * 删除用户操作
     *
     * @return void
     */
    public function deleteUser()
    {
        //判断接收数据类型
        requestGet();
        $userId = input('get.id');
        if (!$userId) {
            echo show('2', '数据不合法');
            exit;
        }
        $res = $this->obj->userDelete($userId);
        if ($res) {
            echo show(0, '删除成功');
        } else {
            echo show(2, '删除失败');
        }
    }

    public function searchUserInfo()
    {
        //判断请求类型
        requestPost();
        $searchInfo = input('post.');
        if (!empty($searchInfo['search_type']) && !empty($searchInfo['search'])) {
            $data = $this->obj->searchUser($searchInfo['search_type'], $searchInfo['search']);
            if ($data) {
                echo show(0, '', $data);
                exit;
            } 
        } else {
            echo show(2, "未选择搜索类型或搜索内容为空");
        }
    }
}
