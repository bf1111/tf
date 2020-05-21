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

    public function showAllCategory()
    {
        $data = $this->obj->categoryShow(0);
        foreach ($data as $key => $val) {
            $data[$key]['category_2'] = $this->obj->categoryShow($val['id']);
            foreach($data[$key]['category_2'] as $key1 => $val2)
            {
                $data[$key]['category_2'][$key1]['category_3'] = $this->obj->categoryShow($val2['id']);
            }
        }
        echo show(0,'',$data);
    }
}
