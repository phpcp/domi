<?php
	use Illuminate\Support\Facades\Hash;
	use Tymon\JWTAuth\Facades\JWTAuth;
	use Tymon\JWTAuth\Facades\JWTFactory;
// JWT 应用公共文件
/**
* 设置TOKEN
*/
function setToken($data,$setTTL="")
{
	$token = JWTAuth::fromUser($data);
	if(!empty($setTTL)){
		JWTFactory::setTTL($setTTL);
	}
	return $token;
	// $res = responseWithToken($token);
	// dd($res->getData(true));
}
function getToken()
{
	$token = JWTAuth::getToken();
	// JWTAuth::setToken($token);
	$user = JWTAuth::toUser();
	dd($user);
	$user = $user->toArray();
	return $user;
}
function JWTrefresh()
{
	JWTAuth::getToken();
	$token = JWTAuth::refresh();
	return $token;
	// return $this->responseWithToken($token);
}
function JWTlogout()
{
	JWTAuth::invalidate(JWTAuth::getToken()); // 即把当前token加入黑名单
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