<?php
namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    //验证的数据
    protected $rule = [
        'name' => [
            'require',
            'min' => 5,
            'max' => 10
        ],
        'password' => [
            'require',
            'min' => 8,
            'max' => 16
        ],
        'repassword' => [
            'require'
        ]
    ];

    //自定义错误提示
    protected $message = [
        'name.require' => '用户名不能为空',
        'name.min' => '用户名长度最少5个字符',
        'name.max' => '用户名长度最多10个字符',
        'password.require' => '密码不能为空',
        'password.min' => '密码长度最少8个字符',
        'password.mix' => '密码长度最少16个字符',
        'repassword.require' => '请重复密码'
    ];

    //验证场景
    protected $scene = [
        'login' => ['name','password'],
        'add' => ['name','password']
    ];
}