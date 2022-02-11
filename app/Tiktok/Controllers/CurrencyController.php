<?php

namespace App\Tiktok\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurrencyController 
{
    //获取语言列表
    public function gain_language(Request $request)
    {
        
    }
    // 模拟登录（只限于测试）
    // public function login_user_dome(Request $request)
    // {
    //     $id = $request->id;
    //     if( empty($id)){
    //         return return_json(1,'获取失败请传入ID！');
    //     }
    //     $user = WanchorUser::where('id','=',$id)->first();
    //     if( empty($user)){
    //         return return_json(1,'获取失败，用户不存在！');
    //     }
    //     if( $user->status == 2 ){
    //         return return_json(1,'获取失败，该用户禁止使用！');
    //     }
    //     $tokenRre = setToken($user);
    //     if( $tokenRre['code'] != 0 ){
    //         return return_json($tokenRre['code'],$tokenRre['msg']);
    //     }
    //     $userArray = $user->toArray();
    //     $userArray['token'] = $tokenRre['data']['token'];
    //     return return_json($tokenRre['code'],$tokenRre['msg'],$userArray);
    // }
}
