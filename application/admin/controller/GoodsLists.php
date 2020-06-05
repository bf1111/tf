<?php

namespace app\admin\controller;

use think\Controller;

class GoodsLists extends Controller
{
    protected $category;
    protected $lists;

    //初始化
    public function initialize()
    {
        $this->category = model('GoodsCategory');  //商品分类模型实例化
        $this->lists = model('GoodsLists');   //商品模型实例化
    }

    //商品分类显示(一二级)
    public function goodsCategoryShow()
    {
        $data = $this->category->categoryShow(0);
        foreach ($data as $key => $val) {
            $data[$key]['category_2'] = $this->category->categoryShow($val['id']);
        }
        echo show(0, '', $data);
    }

    //商品分类显示(三级)
    public function goodsCategoryShowThree()
    {
        //判断是否get请求
        requestGet();

        //接收数据
        $pid = input('get.id');

        if(empty($pid)){
            echo show('2','数据不合法');
            exit();
        }

        //数据库数据查询
        $data = $this->category->categoryShow($pid);
        echo show(0,'',$data);
    }

    //商品添加
    public function goodsAdd()
    {
        //判断是否post请求
        requestPost();

        //接收数据
        $data = input('post.');

        //验证器 验证数据
        $validate = validate('GoodsLists');
        if (!$validate->scene('add')->check($data)) {
            echo show(2, $validate->getError());
            exit;
        }

        //数据入库
        $res = $this->lists->goodsAdd($data);
        echo  $res ? show('0','商品添加成功') : show(2, '服务端错误,商品添加失败');
    }
}
