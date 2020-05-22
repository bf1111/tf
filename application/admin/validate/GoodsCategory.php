<?php
namespace app\admin\validate;

use think\Validate;

class GoodsCategory extends Validate
{

    //验证的数据
    protected $rule = [
        'name' => [
            'require',
            'max' => 12
        ]
    ];

    //自定义错误提示
    protected $message = [
        'name.require' => '商品名不能为空',
        'name.max' => '商品名最大长度不能超过12个字符'
    ];

    protected $scene = [
        'add' => ['name']
    ];
}