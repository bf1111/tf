<?php
namespace app\user\validate;

use think\Validate;

class User extends Validate
{
    //验证的数据
    protected $rule = [
        'name' => [
            'require',
            'min' => 6,
            'max' => 10,
            '/^[A-Za-z0-9]+$/'
        ],
        'password' => [
            'require',
            'min' => 8,
            'max' => 16
        ],
        'repassword' => [
            'require'
        ],
        'email' => [
            'require',
            'email'
        ],
        'phone' => [
            'require',
            '/^1[3-8]{1}[0-9]{9}$/'  //验证手机号
        ],
        'image' => [
            'require'
        ],
        'username' => [
            'require',
            'min' => 1,
            'max' => 10
        ],
        'code' => [
            'require',
            '/^\d{6}$/'
        ]
    ];

    //自定义错误提示
    protected $message = [
        'name.require' => '用户名不能为空',
        'name.min' => '用户名长度最少6个字符',
        'name.max' => '用户名长度最多10个字符',
        'name./^[A-Za-z0-9]+$/' => '只能输入数字和字母',
        'password.require' => '密码不能为空',
        'password.min' => '密码长度最少8个字符',
        'password.max' => '密码长度最少16个字符',
        'repassword.require' => '请重复密码',
        'email.require' => '邮箱不能为空',
        'email.email' => '请填写正确的邮箱',
        'phone.require' => '手机号不能为空',
        'phone./^1[3-8]{1}[0-9]{9}$/' => '请输入正确的手机号',
        'username.require' => '昵称不能为空',
        'username.min' => '昵称最少1个字符',
        'username.max' => '昵称长度最多10个字符',
        'code.require' => '验证码不能为空',
        'code./^\d{6}$/' => '请输入六位数字的验证码'
    ];

    //验证场景
    protected $scene = [
        'register' => ['name','password','repassword','email','phone','code'],
        'login' => ['name','password'],
        'edit' => ['username','password','email','phone'],
        'note' => 'phone'
    ];
}