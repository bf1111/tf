<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\controller\Base;

class Admin extends Controller
{
    protected $obj;

    public function initialize()
    {
        $this->obj = model('Admin');
    }

    /**
     * 管理员登录
     *
     * @return void
     */
    public function adminLogin()
    {
        //判断请求类型
        requestPost();

        //接收数据
        $data = input('post.');

        //验证器 验证数据
        $validate = validate('Admin');
        if(!$validate->scene('login')->check($data)){
            echo show('2',$validate->getError());
            exit;
        }

        //判断账号是否存在
        $res = $this->obj->userExist($data['name']);
        if(!$res){
            echo show('2','用户不存在');
            exit;
        }

        //判断密码是否正确
        if(md5($data['password'].$res['code']) == $res['password']){
            $Base = new Base;  //实力换Base对象
            $token = $Base->setToken();
            session('token',$token);
            echo show(0,'登录成功',$token);
            exit;
        }else{
            echo show(2,'密码不正确');
            exit;
        }
    }

    /**
     * 添加管理员
     *
     * @return void
     */
    public function addAdmin()
    {
        //判断是否post提交
        requestPost();

        //接收数据
        $data = input('post.');

        //验证器  验证数据
        $validate = validate('Admin');
        if (!$validate->scene('add')->check($data)) {
            echo show(2, $validate->getError());
            exit;
        }

        //判断账号是否存在
        if ($this->obj->userExist($data['name'])) {
            echo show(2, "该账号已存在");
            exit;
        }

        //判断两次密码是否一致
        if($data['password'] != $data['repassword']){
            echo show(2,'两次密码不一致');
            exit;
        }

        //获取添加者
        $adminId = session('admin.id','','admin');
        if($adminId){
            $data['add_user_id'] = $adminId;
        }else{
            $data['add_user_id'] = 0;
        }

        // //自动生成  加盐字符串
        $data['code'] = mt_rand(100, 10000);
        $data['password'] = md5($data['password'] . $data['code']);  //密码加密

        //数据入库
        $res = $this->obj->addAdmins($data);
        if($res){
            echo show(0,'添加成功');
        }else{
            echo show(2,'添加失败');
        }
    }
}
