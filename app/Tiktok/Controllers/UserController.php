<?php

namespace App\Tiktok\Controllers;
use App\Models\WanchorUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController 
{
    public function show(Request $request)
    {

        //获取用户
        // $user = WanchorUser::first();
        // 设置TOKEN
        // $token = setToken($user);
        // dd($token);
        //获取用户
        $user = getToken();
        dd($user);
        
        //刷新TOKEN
        // JWTrefresh();
        JWTlogout();
        dd(1);
        $res = $this->responseWithToken(JWTAuth::refresh());
        dd($res);
        $credentials = $request->only('open_id');
        $token = JWTAuth::attempt($credentials);
        dd($token);
        $token_url = "https://open-api.tiktok.com/platform/oauth/connect?client_key={CLIENT_KEY}&scope=user.info.basic,video.list&response_type=code&redirect_uri={SERVER_ENDPOINT_REDIRECT}&state=123123";
    	dd($token_url);
        $token_res = $this->https_request($token_url);
        dd($token_res);
        $token_res = json_decode($token_res, true);
    }
    
    // 模拟 http 请求
    public function https_request($url, $data = null){
        // curl 初始化
        $curl = curl_init();
        // curl 设置
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        // 判断 $data get  or post
        if ( !empty($data) ) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 执行
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }
}
