<?php
namespace app\admin\validate;

use think\Validate;

class GoodsLists extends Validate
{
    //验证的数据
    protected $rule = [
        'name' => [
            'require'
        ],
        'price' => [
            'require'
        ],
        'des_title' => [
            'require'
        ],
        'des_content' => [
            'require'
        ],
        'type' => [
            'require'
        ],
        'category' => [
            'require'
        ],
        'color' => [
            'require'
        ]
    ];

    //自定义错误提示
    protected $message = [
        'name.require' => '请填写商品名称',
        'price.require' => '请填写商品价格',
        'des_title' => '请填写商品描述标题',
        'des_content' => '请填写商品描述正文',
        'type.require' => '请填写商品类型',
        'category.require' => '请选择商品分类',
        'color' => '请填写商品颜色'
    ];

    //验证场景
    protected $scene = [
        'add' => ['name','price','des_title','des_content','type','category','color']
    ];
}