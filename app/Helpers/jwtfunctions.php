<?php
	use Illuminate\Support\Facades\Hash;
	use Tymon\JWTAuth\Facades\JWTAuth;
	use Tymon\JWTAuth\Facades\JWTFactory;
// JWT 应用公共文件

//获取用户
// $user = WanchorUser::first();
// 设置TOKEN
// $token = setToken($user);
// dd($token);
 

//获取用户
// $user = getToken();
// dd($user);

//刷新TOKEN
// $token = JWTrefresh();
// dd($token);
 
//退出
// $Res = JWTlogout();
// dd($Res);
// 
/**
* 设置TOKEN
*/
function setToken($data,$setTTL="")
{
	if( empty($data)){
		$response = [
			'code' => 1,
			'data'	=> [],
			'msg'=> '用户信息 不存在或不正确！'
	    ];
	    return $response;
	}
	$token = JWTAuth::fromUser($data);
	if(!empty($setTTL)){
		JWTFactory::setTTL($setTTL);
	}
	$response = [
		'code' => 0,
		'data'	=> ['token'=>$token],
		'msg'=> '获取成功！'
    ];
    return $response;
	// $res = responseWithToken($token);
	// dd($res->getData(true));
}
function getToken()
{
	$user = JWTAuth::user();
	if( empty($user)){
		$response = [
			'code' => 1,
			'data'	=> [],
			'msg'=> 'Token 不存在或不正确！'
	    ];
	    return $response;
	}
	$response = [
			'code' => 0,
			'data'	=> $user->toArray(),
			'msg'=> '用户信息获取成功'
	    ];
	return $response;
}
function JWTrefresh()
{
	$res = getToken();
	if( $res['code'] == 1 ){
		return $res;
	}
	JWTAuth::getToken();
	$token = JWTAuth::refresh();
	if(empty($token)){
		$response = [
			'code' => 1,
			'data'	=> [],
			'msg'=> '刷新失败！'
	    ];
	    return $response;
	}
	$response = [
		'code' => 0,
		'data'	=> ['token'=>$token],
		'msg'=> '刷新成功！'
    ];
    return $response;
	// return $this->responseWithToken($token);
}
function JWTlogout()
{
	$res = getToken();
	if( $res['code'] == 1 ){
		return $res;
	}
	JWTAuth::invalidate(JWTAuth::getToken()); // 即把当前token加入黑名单
	$response = [
		'code' => 0,
		'data'	=> [],
		'msg'=> '退出成功！'
    ];
    return $response;
}
/**
* 响应
*/
function responseWithToken(string $token)
{
    $response = [
        'access_token' => $token,
        'token_type' => 'Bearer',
        'expires_in' => JWTFactory::getTTL() * 60
    ];

    return response()->json($response);
}