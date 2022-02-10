<?php
	namespace App\Library;

	/**
	* 
	*/
	class Translate
	{
		//翻译入口
		//
		public function translate($query,$to,$from='zh')
		{
			$config = config('public.translate');
		    $args = array(
		        'q' => $query,
		        'appid' => $config['APP_ID'],
		        'salt' => rand(10000,99999),
		        'from' => $from,
		        'to' => $to,
		    );
		    $args['sign'] = Translate::buildSign($query, $config['APP_ID'], $args['salt'], $config['SEC_KEY']);

		    $ret = Translate::call($config['URL'], $args);
		    $ret = json_decode($ret, true);

		    if( empty($ret['error_code'])){
		    	$res['code'] = 0;
		    	$res['msg'] = '获取成功！';
		    	$res['data'] = $ret['trans_result'];
		    	return $res;
		    }else{
		    	$res['code'] = $ret['error_code'];
		    	$res['msg'] = Translate::error_code_msg($ret['error_code']).'----'.$ret['error_msg'];
		    	$res['data'] = [];
		    	return $res;
		    }
		}
		//加密
		protected function buildSign($query, $appID, $salt, $secKey)
		{
		    $str = $appID . $query . $salt . $secKey;
		    $ret = md5($str);
		    return $ret;
		}
		//发起网络请求
		protected function call($url, $args=null, $method="post", $testflag = 0, $timeout='', $headers=array())
		{
			if( empty($timeout)){
				$timeout = $config = config('public.translate.CURL_TIMEOUT');
			}
		    $ret = false;
		    $i = 0; 
		    while($ret === false) 
		    {
		        if($i > 1)
		            break;
		        if($i > 0) 
		        {
		            sleep(1);
		        }
		        $ret = Translate::callOnce($url, $args, $method, false, $timeout, $headers);
		        $i++;
		    }
		    return $ret;
		}
		protected function callOnce($url, $args=null, $method="post", $withCookie = false, $timeout = '', $headers=array())
		{
			if( empty($timeout)){
				$timeout = $config = config('public.translate.CURL_TIMEOUT');
			}

		    $ch = curl_init();
		    if($method == "post") 
		    {
		        $data = Translate::convert($args);
		        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		        curl_setopt($ch, CURLOPT_POST, 1);
		    }
		    else 
		    {
		        $data = Translate::convert($args);
		        if($data) 
		        {
		            if(stripos($url, "?") > 0) 
		            {
		                $url .= "&$data";
		            }
		            else 
		            {
		                $url .= "?$data";
		            }
		        }
		    }
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    if(!empty($headers)) 
		    {
		        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		    }
		    if($withCookie)
		    {
		        curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
		    }
		    $r = curl_exec($ch);
		    curl_close($ch);
		    return $r;
		}
		protected function convert(&$args)
		{
		    $data = '';
		    if (is_array($args))
		    {
		        foreach ($args as $key=>$val)
		        {
		            if (is_array($val))
		            {
		                foreach ($val as $k=>$v)
		                {
		                    $data .= $key.'['.$k.']='.rawurlencode($v).'&';
		                }
		            }
		            else
		            {
		                $data .="$key=".rawurlencode($val)."&";
		            }
		        }
		        return trim($data, "&");
		    }
		    return $args;
		}
		protected function error_code_msg($code)
		{
			switch ($code) {
				case '52000':
					$text = '成功！';
				break;
				case '52001':
					$text = '请求超时，请重试！';
				break;
				case '52002':
					$text = '系统错误，请重试！';
				break;
				case '52003':
					$text = '未授权用户，请检查appid是否正确或者服务是否开通！';
				break;
				case '54000':
					$text = '必填参数为空，请检查是否少传参数！';
				break;
				case '54001':
					$text = '签名错误，请检查您的签名生成方法！';
				break;
				case '54003':
					$text = '访问频率受限， 请降低您的调用频率，或进行身份认证后切换为高级版/尊享版 ！';
				break;
				case '54004':
					$text = '账户余额不足，请前往管理控制台为账户充值！';
				break;
				case '54005':
					$text = '长query请求频繁，请降低长query的发送频率，3s后再试！';
				break;
				case '58000':
					$text = '客户端IP非法，检查个人资料里填写的IP地址是否正确，可前往开发者信息-基本信息修改！';
				break;
				case '58001':
					$text = '译文语言方向不支持，检查译文语言是否在语言列表里！';
				break;
				case '58002':
					$text = '服务当前已关闭，请前往管理控制台开启服务！';
				break;
				case '90107':
					$text = '认证未通过或未生效，请前往我的认证查看认证进度！';
				break;
				default:
					$text = '未知错误！';
				break;
			}
			return $text;
		}
	}