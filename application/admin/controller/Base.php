<?php
namespace app\admin\controller;

use think\Controller;

class Base extends Controller
{
    public function setToken(){
        $token = $this->request->token('__token__', 'sha1');
        return $token;
    }
}