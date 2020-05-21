<?php
namespace app\admin\model;

use think\Model;

class GoodsCategory extends Model
{
    public function categoryAdd()
    {

    }

    public function categoryShow($parentId)
    {
        return $this->where('parent_id',$parentId)->select();
    }
}