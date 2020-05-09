<?php

namespace App\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
class ApiController extends Controller
{
    use Helpers;

    public $token = '';
    public $admininfo = [];
    function __construct(Request $request)
    {
        $this->token =$request->input('token');
        $this->checkToken($this->token);
    }
    public function checkToken($token)
    {
//        if(!isset($token)){
//            return $this->response->errorUnauthorized('请先登陆');
//        }else{
//
//        }
    }

    public function httpget($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko)");
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");//加入gzip解析
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}
