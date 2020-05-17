<?php
namespace app\api\controller;

use think\Controller;

class Token extends Controller
{
    public function setToken(){
        $token = $this->request->token('__token__', 'sha1');
        return $token;
    }
}