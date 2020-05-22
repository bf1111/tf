<?php

namespace app\admin\controller;

use think\Model;

class GoodsCategory extends Model
{
    protected $obj;

    /**
     * 对象初始化
     *
     * @return void
     */
    public function initialize()
    {
        $this->obj = model('GoodsCategory');
    }

    /**
     * 显示全部分类
     *
     * @return void
     */
    public function showAllCategory()
    {
        $data = $this->obj->categoryShow(0);
        foreach ($data as $key => $val) {
            $data[$key]['category_2'] = $this->obj->categoryShow($val['id']);
            foreach ($data[$key]['category_2'] as $key1 => $val2) {
                $data[$key]['category_2'][$key1]['category_3'] = $this->obj->categoryShow($val2['id']);
            }
        }
        echo show(0, '', $data);
    }

    /**
     * 添加分类
     *
     * @return void
     */
    public function addCategory()
    {
        //判断请求是否合法 POST
        requestPost();

        //接收数据
        $data = input('post.');

        //验证器 验证
        $validate = validate('GoodsCategory');
        if (!$validate->scene('add')->check($data)) {
            echo show(2, $validate->getError());
            exit;
        }

        //判断商品分类是否存在
        $res = $this->obj->categoryExist('name', $data['name']);
        if ($res) {
            echo show(2, '此商品名已存在');
            exit;
        }

        //判断上级分类id是否存在
        if (!empty($data['parent_id'])) {
            $res = $this->obj->categoryExist('parent_id', $data['parent_id']);
            if (!$res) {
                echo show(2, '该上级分类不存在');
                exit;
            }
        }

        //处理数据
        if (empty($data['parent_id']) || $data['parent_id'] == null) {
            $data['parent_id'] = 0;
        }

        //数据入库
        $res = $this->obj->categoryAdd($data);
        if ($res) {
            echo show(0, '添加成功');
        } else {
            echo show(2, '服务端错误,添加失败');
        }
    }
}
