<?php

namespace app\user\controller;

use think\Controller;
use app\api\controller\Token;

class User extends Controller
{

    protected $obj;

    /**
     * 初始化对象
     *
     * @return void
     */
    public function initialize()
    {
        $this->obj = model('User');
    }

    /**
     * 添加用户操作
     *
     * @return void
     */
    public function userregister()
    {
        //判断数据是否Post提交
        requestPost();

        //接收数据
        $data = input('post.');

        //验证器，验证数据
        $validate = validate('User');
        if (!$validate->scene('register')->check($data)) {
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
        $res = $this->obj->userPut($data);
        if ($res) {
            echo show(0, '注册成功');
        } else {
            echo show(2, "注册失败");
        }
    }

    /**
     * 用户登录
     *
     * @return void
     */
    public function userlogin()
    {
        //判断请求类型
        requestPost();

        //接收数据
        $data = input('post.');

        //验证器，验证数据
        $validate = validate('User');
        if(!$validate->scene('login')->check($data)){
            echo show(2,$validate->getError());
            exit;
        }

        //判断用户名是否存在
        $res = $this->obj->dataExist('name',$data['name']);
        if(!$res){
            echo show(2,'用户名不存在');
            exit;
        }

        if(md5($data['password'].$res['code']) == $res['password']){
            //令牌
            $Token = new Token();
            $token = $Token->setToken();
            session('token',$token,'index');
            
            //更新数据库信息
            $this->obj->updateLogin($res['id']);

            echo show(0,'登录成功',$token);
            exit;
        }else{
            echo show(2,'密码错误');
            exit;
        }
    }
}
