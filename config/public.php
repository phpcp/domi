<?php
//通用配置
return [
    'translate' => [
        'CURL_TIMEOUT' => 10,
        'URL' => "http://api.fanyi.baidu.com/api/trans/vip/translate",
        'APP_ID' => "20220209001078253",
        'SEC_KEY' => "R6IqTCgeHOCq0YYAX0Fv",
    ],
    'baidu_translate' => [
    	'TOKENURL' => 'https://aip.baidubce.com/oauth/2.0/token',
    	'URL'=>'https://aip.baidubce.com/rpc/2.0/mt/texttrans/v1',
    	'APP_ID' => "25150558",
    	'APP_KEY'=>"UldGsM1H2Cnxajf6iuZm97iX",
        'SEC_KEY' => "eEHIsiZdqfxflhAjGEN3NdY7HVSm8kzU",
    ]
];
