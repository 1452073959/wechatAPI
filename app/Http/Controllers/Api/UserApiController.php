<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserApiController extends ApiController
{


    public function show(Request $request, $id = 1)
    {
        $user = User::find($id);
        return $user;
    }

    public function paginate()
    {
        $user = User::paginate(2);
        return $user;
    }

    public function code(Request $request)
    {
        $code=$request->input('code');

//        $url="https://api.weixin.qq.com/sns/jscode2session?appid=wx0f8e31fa7cdd52bd&secret=61ec689d9f424b5c969a44481d83035f&js_code=" +
//            "&grant_type=authorization_code"
        $url="https://api.weixin.qq.com/sns/jscode2session?appid=wx0f8e31fa7cdd52bd&secret=61ec689d9f424b5c969a44481d83035f&js_code=".$code."&grant_type=authorization_code";
        $verify= json_decode($this->httpget($url),true);
//        dd($verify);
        $user=User::where('openid',$verify['openid'])->update(['remember_token'=>$verify['session_key']]);
         Cache::put($verify['openid'], $verify['session_key']);
        return $verify;

    }
    //保存及更新
    public function updateUserInfo(Request $request)
    {
        $data=$request->all();
        $exist=User::where('openid',$data['openid'])->first();
        if(empty($exist)){
            $user=new User();
            $user->name=$data['nickName'];
            $user->email=$data['avatarUrl'];
            $user->openid=$data['openid'];
            $user->save();

        }else{
            $exist->name=$data['nickName'];
            $exist->email=$data['avatarUrl'];
            $exist->openid=$data['openid'];
            $exist->save();

        }
        return $data;
    }

    public function cache()
    {
        $value= Cache::put('姓名', '姓名', 3000);
        $get = Cache::get('姓名');
        dd($get);
    }
    public function upload(Request $request)
    {
//        dump($request->all());
        $path =  $request->file('file')->store('images', 'public');
        $path='http://192.168.1.97/storage/'.$path;
//        {"error":0,"info":"http:\/\/a3q.dns06.net.cn\/15889181301441.jpeg"}
       $res= ['info'=>$path];
        return $res;
//        $user=User::where('remember_token',$request->token)->first();
//        dd($path);
    }

    public function dy()
    {
        $data=date('y-m-d h:i:s',time());
        echo $data;
       $token= $this->getAccessToken('wx0f8e31fa7cdd52bd','61ec689d9f424b5c969a44481d83035f');
//       echo $token;
        $url= "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=".$token;

        $content=[
//            'access_token'=>$token,
            'touser'=>"oUf9a5AT4xPDZy8lJ5Fpskh5gTqE",
            'template_id'=>'JijPxhl-oR5WSGzfYzep8ZbrBY-gPEnJPur0RSqgbp0',
            'data'=>[
                'date1'=>['value' =>$data ],
                'phrase2'=>['value' => '新闻栏目'],
                'phrase3'=>['value' => '每日新闻'],
                'thing4'=>['value' =>'朱晨晨'],
             ],
        ];
        $content=json_encode($content);
//        dd($content);
       $a= $this->HttpPost($content,$url);
        print_r($a);

    }

    public function getAccessToken($appid,$appsecret)
    {
//        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET";
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $appid . '&secret=' . $appsecret;
        $verify= json_decode($this->httpget($url),true);
        $verify= $verify['access_token'];
        return $verify;
    }


    public function HttpPost($content = null,$url='') {
        $postUrl = $url;
        $curlPost = $content;
        $ch = curl_init (); // 初始化curl
        curl_setopt ( $ch, CURLOPT_URL, $postUrl ); // 抓取指定网页
        curl_setopt ( $ch, CURLOPT_HEADER, 0 ); // 设置header
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); // 要求结果为字符串且输出到屏幕上
        curl_setopt ( $ch, CURLOPT_POST, 1 ); // post提交方式
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $curlPost );
        $data = curl_exec ( $ch ); // 运行curl
        curl_close ( $ch );
        return $data;
    }



}
