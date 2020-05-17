<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//传给前端数据
function show($status, $msg, $data = [])
{
    return json_encode([
        'status' => $status,
        'msg' => $msg,
        'data' => $data
    ]);
}

//判断是否为Post提交
function requestPost()
{
    if (!request()->isPost()) {
        echo show("2", "请求不合法");
        exit;
    }
}

//判断是否为Get提交
function requestGet()
{
    if (!request()->isGet()) {
        echo show("2", "请求不合法");
        exit;
    }
}
