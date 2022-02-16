<?php

namespace App\Tiktok\Controllers;
use App\Models\WanchorUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WanchorUserController 
{
    // 模拟登录（只限于测试）
    public function login_user_dome(Request $request)
    {   
        $res = GeoIp();
        dd($res);
        $id = $request->id;
        if( empty($id)){
            return return_json(1,'获取失败请传入ID！');
        }
        $user = WanchorUser::where('id','=',$id)->first();
        if( empty($user)){
            return return_json(1,'获取失败，用户不存在！');
        }
        if( $user->status == 2 ){
            return return_json(1,'获取失败，该用户禁止使用！');
        }
        $tokenRre = setToken($user);
        if( $tokenRre['code'] != 0 ){
            return return_json($tokenRre['code'],$tokenRre['msg']);
        }
        $userArray = $user->toArray();
        $userArray['token'] = $tokenRre['data']['token'];
        return return_json($tokenRre['code'],$tokenRre['msg'],$userArray);
    }
    //设置用户语言
    public function save_lang_iso(Request $request)
    {
        $lang_iso = $request->lang_iso;
        $user = getToken();
        if( $user['code'] != 0 ){
            return return_json(1,$user['msg']);
        }
        $u_id = $user['data']['id'];
        WanchorUser::where('id',$u_id)->update(['lang_iso'=>$lang_iso]);
        return return_json(0,'设置成功！',$lang_iso);
    }
    
    public function show(Request $request)
    {
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
