<?php
namespace app\admin\model;

use think\Model;

class GoodsLists extends Model
{
    /**
     * 添加商品
     *
     * @param [type] $data  商品数据
     * @return void
     */
    public function goodsAdd($data)
    {
        $data['ctime'] = time();
        return $this->allowField(true)->save($data);
    }
}