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
        // session('token', '111');
        if (!session('token')) {
            echo show(10, '请登录');
            exit;
        }
        //User模板
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

    /**
     * 商品分类删除
     *
     * @return void
     */
    public function categoryDelete()
    {
        //判断请求类型  get
        requestGet();

        //接收数据
        $id = input('get.id');

        //处理数据
        if (!$id) {
            echo show(0, '数据不合法');
            exit;
        }

        $res = $this->obj->categoryDel($id);
        if ($res) {
            echo show(0, '删除成功');
        } else {
            echo show(2, '删除失败');
        }
    }

    /**
     * 分类编辑(数据显示) 
     *
     * @return void
     */
    public function getEditCategory()
    {
        //判断请求类型  GET
        requestGet();

        //接收数据
        $id = input('get.id');

        if ($id) {
            //数据库  查询用户信息
            $data = $this->obj->getOneCategory($id);
            echo show(0, "", $data);
        } else {
            echo show(2, "数据错误");
        }
    }

    /**
     * 分类编辑(数据更新)
     *
     * @return void
     */
    public function userEditPost()
    {
        //判断请求类型  POST
        requestPost();

        //接收数据
        $data = input('post.');

        //验证器 验证数据
        $validate = validate('GoodsCategory');
        if (!$validate->scene('add')->check($data)) {
            echo show(2, $validate->getError());
            exit;
        }

        //判断数据分类名称是否存在
        if ($this->obj->categoryNameExist($data['name'], $data['id'])) {
            echo show('2', '该商品分类名称已经存在');
            exit;
        }

        //更新数据
        $res = $this->obj->updateCategory($data, $data['id']);
        if ($res) {
            echo show(0, '编辑成功');
        } else {
            echo show(2, '编辑失败');
        }
    }

    /**
     * 一级二级分类数据显示
     *
     * @return void
     */
    public function oneTwoCategory()
    {
        $dataId = [];
        $data = $this->obj->categoryShow(0);
        for($i=0;$i<count($data);$i++)
        {
            $dataId[] = $data[$i]['id'];
        }
        
        $data_2 = $this->obj->getTwoCatagory($dataId);
        
        echo show(0,'',[$data,$data_2]);
        exit;
    }
}
