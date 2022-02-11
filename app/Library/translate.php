<?php
	namespace App\Library;
	use Illuminate\Support\Facades\DB;
	/**
	* 
	*/
	class Translate
	{
		//翻译入口 翻译一
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

				case '1':
					$text = '未知错误，请重试！';
				break;
				case '2':
					$text = '服务处理超时，请重试！';
				break;
				case '4':
					$text = '集群超限额，请重试！';
				break;
				case '6':
					$text = '没有接口权限，请确认您调用的接口已经被赋权。企业认证生效时间为1小时左右，使用需要企业认证的服务，请等待生效后重试！';
				break;
				case '18':
					$text = 'QPS超限额，请降低您的调用频率！';
				break;
				case '19':
					$text = '请求总量超限额，请检查当前可用字符/次数包额度！';
				break;
				case '100':
					$text = 'token拉取失败，无效的access_token参数，参考“Access Token重新获取”！';
				break;
				case '110':
					$text = 'Access Token失效，token有效期为30天，注意需要定期更换，也可以每次请求都拉取新token！';
				break;
				case '111':
					$text = 'Access token过期，token有效期为30天，注意需要定期更换，也可以每次请求都拉取新token！';
				break;

				case '31001':
					$text = '其他错误，请重试！';
				break;
				case '31005':
					$text = '用户用量超限，请检查当前可用字符/次数包额度！';
				break;
				case '31006':
					$text = '内部错误，请重试！';
				break;
				case '31101':
					$text = '请求超时，请重试！';
				break;
				case '31102':
					$text = '系统错误，请重试！';
				break;
				case '31103':
					$text = '必填参数为空或固定参数有误，请检查参数是否为空或误传！';
				break;
				case '31104':
					$text = '访问频率受限，请降低您的调用频率！';
				break;
				case '31105':
					$text = '译文语言方向不支持，请检查译文语言是否在语言列表里！';
				break;

				case '31106':
					$text = 'query字符超过最大长度，请减少翻译译文的长度（最长不得超过6000字节）！';
				break;
				case '31201':
					$text = '请求翻译的原文太长，请减少翻译译文的长度（最长不得超过6000字节）！';
				break;
				case '31202':
					$text = '请求翻译的原文为空，请检查翻译原文内容是否为空！';
				break;
				case '31203':
					$text = '请求翻译的参数有误(目前校验header/param中鉴权必要参数不能为空)，请检查参数是否为空或误传！';
				break;
				case '282000':
					$text = '内部错误，请重试！';
				break;
				case '282003':
					$text = '请求翻译时存在必填参数为空，请检查必填参数（比如q、from、to等）是否为空！';
				break;
				default:
					$text = '未知错误！';
				break;
			}
			return $text;
		}
		//翻译入口 翻译二
		/**
		 * Send post request.
		 *
		 * @param string $url
		 * @param string $q
		 * @param string $from
		 * @param string $to
		 * @return mixed
		 */
		public function sendPostRequest(string $q, string $to, string $from="zh",string $termIds = '')
		{
			$TOKENURL = config('public.baidu_translate.TOKENURL');
		    $post_data['grant_type']       = 'client_credentials';
		    $post_data['client_id']      = config('public.baidu_translate.APP_KEY');
		    $post_data['client_secret'] = config('public.baidu_translate.SEC_KEY');
		    $o = "";
		    foreach ( $post_data as $k => $v ) {
		    	$o.= "$k=" . urlencode( $v ). "&" ;
		    }
		    $post_data = substr($o,0,-1);
		    $token = Translate::request_post($TOKENURL, $post_data);
		    $token = json_decode($token, true);

		    if( empty($token['error'])){
		    	$access_token = $token['access_token'];
		    }else{
		    	$res['code'] = $token['error'];
		    	if( $token['error_description'] == 'unknown client id'){
		    		$res['msg'] = 'API Key不正确';
		    	}else if( $token['error_description'] == 'Client authentication failed' ){
		    		$res['msg'] = 'Secret Key不正确';
		    	}
		    	$res['data'] = [];
		    	return $res;
		    }
			$header = [];
		    $formData = json_encode([
		        'from' => $from,
		        'to' => $to,
		        'q' => $q,
		        'termIds' => $termIds,
		    ]);
		    $url = config('public.baidu_translate.URL').'?access_token=' . $access_token;
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		    curl_setopt($ch, CURLOPT_POST, 1);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		    $ret = curl_exec($ch);
		    curl_close($ch);
		    $ret = json_decode($ret, true);
		    if( empty($ret['error_code'])){
		    	$res['code'] = 0;
		    	$res['msg'] = '获取成功！';
		    	$res['data'] = $ret['result']['trans_result'];
		    	return $res;
		    }else{
		    	dd($ret);
		    	$res['code'] = $ret['error_code'];
		    	$res['msg'] = Translate::error_code_msg($ret['error_code']).'----'.$ret['error_msg'];
		    	$res['data'] = [];
		    	return $res;
		    }
		}
		protected function request_post($url = '', $param = '') {
	        if (empty($url) || empty($param)) {
	            return false;
	        }
	        $postUrl = $url;
	        $curlPost = $param;
	        $curl = curl_init();//初始化curl
	        curl_setopt($curl, CURLOPT_URL,$postUrl);//抓取指定网页
	        curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
	        curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	        $data = curl_exec($curl);//运行curl
	        curl_close($curl);
	        return $data;
	    }
	}