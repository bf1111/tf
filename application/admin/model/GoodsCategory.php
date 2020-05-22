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
    public function categoryExist($name)
    {
        return $this->where('name',$name)->find();
    }
}
