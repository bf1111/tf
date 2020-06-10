<?php

namespace app\api\controller;

use think\Controller;

class ImgUpload extends Controller
{
    public function uploadImg($imgType = ['img', 'jpg', 'jpeg', 'png', 'gif'])
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        if(!$file){
            echo show(2,'请上传图片');
            exit;
        }
        //允许上传的图片类型
        //获取图片后缀
        $type = pathinfo($file->getInfo()['name'], PATHINFO_EXTENSION);

        //判断上传的文件类型是否在允许上传的类型种
        if (!in_array($type, $imgType)) {
            echo show(2, "允许上传的图片类型为img、jpg、jpeg、png、gif");
            exit;
        }
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move('../uploads');
        if ($info) {
            // 成功上传后 获取上传的文件名信息
            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            return show(0, $info->getSavename());;
        } else {
            // 上传失败获取错误信息
            return show(2, $file->getError());
        }
    }

    public function index()
    {
        return $this->fetch();
    }
}
