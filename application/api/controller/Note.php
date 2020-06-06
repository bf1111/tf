<?php

namespace app\api\controller;

use think\Controller;

class Note extends Controller
{
    public function sendNote($apikey, $mobile, $text)
    {
        // 开启句柄
        $ch = curl_init();

        /* 设置验证方式 */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept:text/plain;charset=utf-8',
            'Content-Type:application/x-www-form-urlencoded', 'charset=utf-8'
        ));

        /* 设置返回结果为流 */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /* 设置超时时间*/
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        /* 设置通信方式 */
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // 发送短信
        $data = array('text' => $text, 'apikey' => $apikey, 'mobile' => $mobile);
        $json_data = $this->send($ch, $data);
        $array = json_decode($json_data, true);
        // echo '<pre>';
        // print_r($array);
        // 关闭句柄
        curl_close($ch);
        return $array;
    }

    /**
     * 发送
     *
     * @param [type] $ch   由 curl_init() 返回的 cURL 句柄。
     * @param [type] $data 发送的数据
     * @return void
     */
    public function send($ch, $data)
    {
        curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($ch);
        $error = curl_error($ch);
        $this->checkErr($result, $error);
        return $result;
    }

    /**
     * 检查发送是否错误
     *
     * @param [type] $result 结果
     * @param [type] $error 报错信息
     * @return void
     */
    public function checkErr($result, $error)
    {
        if ($result === false) {
            echo 'Curl error: ' . $error;
        }
    }
}
