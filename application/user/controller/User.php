<?php

namespace app\user\controller;

use think\Controller;
use app\api\controller\Token;
use app\api\controller\Note;
use think\facade\Session;

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
     * 用户注册
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

        //判断验证码是否正确
        if (session('code') !== $data['code']) {
            echo show('2', '验证码不正确');
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
        if (!$validate->scene('login')->check($data)) {
            echo show(2, $validate->getError());
            exit;
        }

        //判断用户名是否存在
        $res = $this->obj->dataExist('name', $data['name']);
        if (!$res) {
            echo show(2, '用户名不存在');
            exit;
        }

        if (md5($data['password'] . $res['code']) == $res['password']) {
            //令牌
            $Token = new Token();
            $token = $Token->setToken();
            session('token', $token, 'index');
            session('user', $res);

            //更新数据库信息
            $this->obj->updateLogin($res['id']);

            echo show(0, '登录成功', $token);
            exit;
        } else {
            echo show(2, '密码错误');
            exit;
        }
    }

    /**
     * 用户退出
     *
     * @return void
     */
    public function loginout()
    {
        session(null);
        if (empty(session('user')) && empty(session('token'))) {
            echo show(9, '成功退出');
        } else {
            echo show(2, '服务端错误');
        }
    }

    /**
     * 得到短信数据
     *
     * @return void
     */
    public function getNotes()
    {
        //判断是否post请求
        requestPost();

        //接收数据
        $mobile = input('post.');

        //验证器验证数据
        $validate = validate('User');
        if (!$validate->scene('note')->check($mobile)) {
            echo show(2, $validate->getError());
            exit;
        }

        //生成验证码 & 存入session
        $code = $this->getCode();
        session('code', $code);

        //发送短信
        $result = $this->sendNotes($code, $mobile['phone']);
        if ($result !== true) {
            echo show('2', $result);
            exit;
        }
    }


    /**
     * 发送短信
     *
     * @param [type] $code 验证码
     * @param [type] $mobile  手机号
     * @return void
     */
    public function sendNotes($code, $mobile)
    {
        //本次能否发送
        $flag = false;
        if(empty(session('send_last_time'))){
            $flag = true;
        }else{
            $nowTime = time();
            if($nowTime - session('send_last_time') > 60){
                $flag = true;
            }
        }

        //能否发送
        if(!$flag){
            echo show('2','请不要频繁点击');
            exit;
        }else{
            $Note = new Note();
            $text = config('text_start') . $code . config('text_end');
            $note = $Note->sendNote(config('apikey'), $mobile, $text);
            session('send_last_time',time());
            if ($note['code'] == 0) {
                return true;
            } else {
                return $note['msg'];
            }
        }
    }

    /**
     * 获取六位验证码
     *
     * @return void
     */
    public function getCode()
    {
        $code = '';
        for ($i = 0; $i <= 5; $i++) {
            $code .= mt_rand(0, 9);
        }
        return $code;
    }
}
