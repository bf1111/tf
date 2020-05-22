<?php

namespace app\admin\model;

use think\Model;

class GoodsCategory extends Model
{
    /**
     * 添加分类
     *
     * @param [type] $data  数据
     * @return void
     */
    public function categoryAdd($data)
    {
        $data['ctime'] = time();
        return $this->allowField(true)->save($data);
    }

    /**
     * 分类显示
     *
     * @param [type] $parentId  父代id
     * @return void
     */
    public function categoryShow($parentId)
    {
        return $this->where(['parent_id' => $parentId, 'list_status' => 1])->select();
    }

    /**
     * 判断商品分类名是否存在
     *
     * @param [type] $name
     * @return void
     */
    public function categoryExist($list, $value)
    {
        return $this->where($list, $value)->find();
    }

    /**
     * 商品分类删除
     *
     * @param [type] $id  商品id
     * @return void
     */
    public function categoryDel($id)
    {
        return $this->save(['list_status' => -1], ['id' => $id]);
    }

    /**
     * 返回一条分类数据
     *
     * @param [type] $id  分类id
     * @return void
     */
    public function getOneCategory($id)
    {
        $data = [
            'id' => $id,
            'list_status' => 1
        ];
        return $this->where($data)->find();
    }

    /**
     * 商品分类数据更新
     *
     * @param [type] $data  更新数据
     * @param [type] $id   商品分类id
     * @return void
     */
    public function updateCategory($data, $id)
    {
        $data['edit_time'] = time();
        return $this->save($data, ['id' => $id]);
    }

    /**
     * 商品分类编辑的是否判断数据是否存在
     *
     * @param [type] $list  字段
     * @param [type] $data  判断的数据
     * @param [type] $id   跳过的id
     * @return void
     */
    public function categoryNameExist($data, $id)
    {
        $res = $this->where(['name' => $data, 'list_status' => 1])->where('id', 'neq', $id)->find();
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public function getTwoCatagory($array)
    {
        return $this->where('list_status',1)->where('parent_id','in',$array)->select();
    }

}
