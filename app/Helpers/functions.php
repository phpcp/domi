<?php
use GeoIp2\Database\Reader;
// 应用公共文件
/**
 * 数据表格数据格式
 * @param [type] $msg
 * @param [type] $count
 * @param [type] $data
 * @param integer $code
 */
function return_json(int $code,string $msg,$data = "", int $count = 0,$record="")
{
    if( empty($record)){
        return response()->json(compact('code', 'msg', 'count', 'data'));
    }else{
        return response()->json(compact('code', 'msg', 'count', 'data','record'));
    }
}
/**
 * 禁用Pjax
 */
function noPjax()
{
    $request = \Request::instance();
    if ($request->headers->has("X-PJAX")) {
        $request->headers->set("X-PJAX", false);
    }
}
//base64 转图片
function base64_upload($base64,$path) {
    
    $base64_image = str_replace(' ', '+', $base64);
    //post的数据里面，加号会被替换为空格，需要重新替换回来，如果不是post的数据，则注释掉这一行
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)){
        //匹配成功
        if($result[2] == 'jpeg'){
            $image_name = uniqid().'.jpg';
            //纯粹是看jpeg不爽才替换的
        }else{
            $image_name = uniqid().'.'.$result[2];
        }
        mkdirs("./upload/".$path);
        $image_file = "./upload/".$path."{$image_name}";
        //服务器文件存储路径
        if (file_put_contents($image_file, base64_decode(str_replace($result[1], '', $base64_image)))){
            return $image_name;
            
        }else{
            return false;
        }
    }else{
        return false;
    }
}
//创建文件夹
function mkdirs($dir, $mode = 0777)
{
    if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
    if (!mkdirs(dirname($dir), $mode)) return FALSE;
    return @mkdir($dir, $mode);
} 
/**
 * @description:根据数据 
 * @param {dataArr:需要分组的数据；keyStr:分组依据} 
 * @return: array
 */
function dataGroup(array $dataArr,$keyStr)
{
    $newArr=[];
    foreach ($dataArr as $k => $val) {    
        $newArr[$val[$keyStr]][] = $val;
    }
    return $newArr;
}

function GeoIp($ip = '')
{
    $ip = empty($ip)?$_SERVER["REMOTE_ADDR"]:$ip;
    $reader = new Reader(public_path().'/GeoLite2-City.mmdb');
    try {
        $record = $reader->city($ip);
        $array = json_decode(json_encode($record),TRUE);
        // dd($array['city']);                          //城市
        // dd($array['continent']);                     //州
        // dd($array['country']);                       //国家
        // dd($array['location']);                      //坐标/时区
        // dd($array['registered_country']);            //注册国家
        // dd($array['subdivisions']);                  //归属地 
        // dd($array['traits']);                        //互联网协议地址
        $response = [
            'code' => 0,
            'data'  => $array,
            'msg'=> '获取成功！'
        ];
        return $response;
    } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
        $response = [
            'code' => 1,
            'data'  => [],
            'msg'=> '输入IP没有找到记录！'
        ];
        return $response;
    } catch (\MaxMind\Db\InvalidDatabaseExceptionn $e) {
        $response = [
            'code' => 1,
            'data'  => [],
            'msg'=> '数据库无效或损坏！'
        ];
        return $response;
    }catch ( \Exception $e ){
        $response = [
            'code' => 1,
            'data'  => [],
            'msg'=> '其他错误！'
        ];
        return $response;
    }
}
function array_iso()
{
    return [
        "阿鲁巴" => "AA"
        ,"安道尔" => "AD"
        ,"阿联酋" => "AE"
        ,"阿富汗" => "AF"
        ,"安提瓜和巴布达" => "AG"
        ,"阿尔巴尼亚" => "AL"
        ,"亚美尼亚" => "AM"
        ,"荷属安德列斯" => "AN"
        ,"安哥拉" => "AO"
        ,"南极洲" => "AQ"
        ,"阿根廷" => "AR"
        ,"东萨摩亚" => "AS"
        ,"奥地利" => "AT"
        ,"澳大利亚" => "AU"
        ,"阿塞拜疆" => "AZ"
        ,"安圭拉岛" => "Av"
        ,"波黑" => "BA"
        ,"巴巴多斯" => "BB"
        ,"孟加拉" => "BD"
        ,"比利时" => "BE"
        ,"布基纳法索" => "BF"
        ,"保加利亚" => "BG"
        ,"巴林" => "BH"
        ,"布隆迪" => "BI"
        ,"贝宁" => "BJ"
        ,"百慕大" => "BM"
        ,"文莱布鲁萨兰" => "BN"
        ,"玻利维亚" => "BO"
        ,"巴西" => "BR"
        ,"巴哈马" => "BS"
        ,"不丹" => "BT"
        ,"布韦岛" => "BV"
        ,"博茨瓦纳" => "BW"
        ,"白俄罗斯" => "BY"
        ,"伯里兹" => "BZ"
        ,"加拿大" => "CA"
        ,"柬埔寨" => "KH"
        ,"可可斯群岛" => "CC"
        ,"刚果" => "CG"
        ,"中非" => "CF"
        ,"瑞士" => "CH"
        ,"象牙海岸" => "CI"
        ,"库克群岛" => "CK"
        ,"智利" => "CL"
        ,"喀麦隆" => "CM"
        ,"中国" => "CN"
        ,"哥伦比亚" => "CO"
        ,"哥斯达黎加" => "CR"
        ,"捷克斯洛伐克" => "CS"
        ,"古巴" => "CU"
        ,"佛得角" => "CV"
        ,"圣诞岛" => "CX"
        ,"塞普路斯" => "CY"
        ,"捷克" => "CZ"
        ,"德国" => "DE"
        ,"吉布提" => "DJ"
        ,"丹麦" => "DK"
        ,"多米尼加共和国" => "DM"
        ,"多米尼加联邦" => "DO"
        ,"阿尔及利亚" => "DZ"
        ,"厄瓜多尔" => "EC"
        ,"爱沙尼亚" => "EE"
        ,"埃及" => "EG"
        ,"西撒哈拉" => "EH"
        ,"厄立特里亚" => "ER"
        ,"西班牙" => "ES"
        ,"埃塞俄比亚" => "ET"
        ,"芬兰" => "FI"
        ,"斐济" => "FJ"
        ,"福兰克群岛" => "FK"
        ,"米克罗尼西亚" => "FM"
        ,"法罗群岛" => "FO"
        ,"法国" => "FR"
        ,"法国-主教区" => "FX"
        ,"加蓬" => "GA"
        ,"英国" => "UK"
        ,"格林纳达" => "GD"
        ,"格鲁吉亚" => "GE"
        ,"法属圭亚那" => "GF"
        ,"加纳" => "GH"
        ,"直布罗陀" => "GI"
        ,"格陵兰岛" => "GL"
        ,"冈比亚" => "GM"
        ,"几内亚" => "GN"
        ,"法属德洛普群岛" => "GP"
        ,"赤道几内亚" => "GQ"
        ,"希腊" => "GR"
        ,"S. Georgia and S. Sandwich Isls." => "GS"
        ,"危地马拉" => "GT"
        ,"关岛" => "GU"
        ,"几内亚比绍" => "GW"
        ,"圭亚那" => "GY"
        ,"中国香港特区" => "HK"
        ,"赫德和麦克唐纳群岛" => "HM"
        ,"洪都拉斯" => "HN"
        ,"克罗地亚" => "HR"
        ,"海地" => "HT"
        ,"匈牙利" => "HU"
        ,"印度尼西亚" => "ID"
        ,"爱尔兰" => "IE"
        ,"以色列" => "IL"
        ,"印度" => "IN"
        ,"英属印度洋领地" => "IO"
        ,"伊拉克" => "IQ"
        ,"伊朗" => "IR"
        ,"冰岛" => "IS"
        ,"意大利" => "IT"
        ,"牙买加" => "JM"
        ,"约旦" => "JO"
        ,"日本" => "JP"
        ,"肯尼亚" => "KE"
        ,"吉尔吉斯斯坦" => "KG"
        ,"基里巴斯" => "KI"
        ,"科摩罗" => "KM"
        ,"圣基茨和尼维斯" => "KN"
        ,"韩国" => "KP"
        ,"朝鲜" => "KR"
        ,"科威特" => "KW"
        ,"开曼群岛" => "KY"
        ,"哈萨克斯坦" => "KZ"
        ,"老挝" => "LA"
        ,"黎巴嫩" => "LB"
        ,"圣卢西亚" => "LC"
        ,"列支顿士登" => "LI"
        ,"斯里兰卡" => "LK"
        ,"利比里亚" => "LR"
        ,"莱索托" => "LS"
        ,"立陶宛" => "LT"
        ,"卢森堡" => "LU"
        ,"拉托维亚" => "LV"
        ,"利比亚" => "LY"
        ,"摩洛哥" => "MA"
        ,"摩纳哥" => "MC"
        ,"摩尔多瓦" => "MD"
        ,"马达加斯加" => "MG"
        ,"马绍尔群岛" => "MH"
        ,"马其顿" => "MK"
        ,"马里" => "ML"
        ,"缅甸" => "MM"
        ,"蒙古" => "MN"
        ,"中国澳门特区" => "MO"
        ,"北马里亚纳群岛" => "MP"
        ,"法属马提尼克群岛" => "MQ"
        ,"毛里塔尼亚" => "MR"
        ,"蒙塞拉特岛" => "MS"
        ,"马耳他" => "MT"
        ,"毛里求斯" => "MU"
        ,"马尔代夫" => "MV"
        ,"马拉维" => "MW"
        ,"墨西哥" => "MX"
        ,"马来西亚" => "MY"
        ,"莫桑比克" => "MZ"
        ,"纳米比亚" => "NA"
        ,"新卡里多尼亚" => "NC"
        ,"尼日尔" => "NE"
        ,"诺福克岛" => "NF"
        ,"尼日利亚" => "NG"
        ,"尼加拉瓜" => "NI"
        ,"荷兰" => "NL"
        ,"挪威" => "NO"
        ,"尼泊尔" => "NP"
        ,"瑙鲁" => "NR"
        ,"中立区(沙特-伊拉克间)" => "NT"
        ,"纽爱" => "NU"
        ,"新西兰" => "NZ"
        ,"阿曼" => "OM"
        ,"巴拿马" => "PA"
        ,"秘鲁" => "PE"
        ,"法属玻里尼西亚" => "PF"
        ,"巴布亚新几内亚" => "PG"
        ,"菲律宾" => "PH"
        ,"巴基斯坦" => "PK"
        ,"波兰" => "PL"
        ,"圣皮艾尔和密克隆群岛" => "PM"
        ,"皮特克恩岛" => "PN"
        ,"波多黎各" => "PR"
        ,"葡萄牙" => "PT"
        ,"帕劳" => "PW"
        ,"巴拉圭" => "PY"
        ,"卡塔尔" => "QA"
        ,"法属尼留旺岛" => "RE"
        ,"罗马尼亚" => "RO"
        ,"俄罗斯" => "RU"
        ,"卢旺达" => "RW"
        ,"沙特阿拉伯" => "SA"
        ,"塞舌尔" => "SC"
        ,"苏丹" => "SD"
        ,"瑞典" => "SE"
        ,"新加坡" => "SG"
        ,"圣赫勒拿" => "SH"
        ,"斯罗文尼亚" => "SI"
        ,"斯瓦尔巴特和扬马延岛" => "SJ"
        ,"斯洛伐克" => "SK"
        ,"塞拉利昂" => "SL"
        ,"圣马力诺" => "SM"
        ,"塞内加尔" => "SN"
        ,"索马里" => "SO"
        ,"苏里南" => "SR"
        ,"圣多美和普林西比" => "ST"
        ,"前苏联" => "SU"
        ,"萨尔瓦多" => "SV"
        ,"叙利亚" => "SY"
        ,"斯威士兰" => "SZ"
        ,"所罗门群岛" => "Sb"
        ,"特克斯和凯科斯群岛" => "TC"
        ,"乍得" => "TD"
        ,"法国南部领地" => "TF"
        ,"多哥" => "TG"
        ,"泰国" => "TH"
        ,"塔吉克斯坦" => "TJ"
        ,"托克劳群岛" => "TK"
        ,"土库曼斯坦" => "TM"
        ,"突尼斯" => "TN"
        ,"汤加" => "TO"
        ,"东帝汶" => "TP"
        ,"土尔其" => "TR"
        ,"特立尼达和多巴哥" => "TT"
        ,"图瓦卢" => "TV"
        ,"中国台湾省" => "TW"
        ,"坦桑尼亚" => "TZ"
        ,"乌克兰" => "UA"
        ,"乌干达" => "UG"
        ,"美国海外领地" => "UM"
        ,"美国" => "US"
        ,"乌拉圭" => "UY"
        ,"乌兹别克斯坦" => "UZ"
        ,"梵蒂岗" => "VA"
        ,"圣文森特和格陵纳丁斯" => "VC"
        ,"委内瑞拉" => "VE"
        ,"英属维京群岛" => "VG"
        ,"美属维京群岛" => "VI"
        ,"越南" => "VN"
        ,"瓦努阿鲁" => "VU"
        ,"瓦里斯和福图纳群岛" => "WF"
        ,"西萨摩亚" => "WS"
        ,"也门" => "YE"
        ,"马约特岛" => "YT"
        ,"南斯拉夫" => "YU"
        ,"南非" => "ZA"
        ,"赞比亚" => "ZM"
        ,"扎伊尔" => "ZR"
        ,"津巴布韦" => "ZW"
    ];
}
function array_baidu()
{
    return [
        '阿拉伯语'=>'ara',
        '爱尔兰语'=>'gle',
        '奥克语'=>'oci',
        '阿尔巴尼亚语' => 'alb',
        '阿尔及利亚阿拉伯语'=>'arq',
        '阿肯语'=> 'aka',
        '阿拉贡语'=>'arg',
        '阿姆哈拉语'=> 'amh',
        '阿萨姆语'=>'asm',
        '艾马拉语'=>'aym',
        '阿塞拜疆语'=>'aze',
        '阿斯图里亚斯语'=> 'ast',
        '奥塞梯语'=>'oss',
        '爱沙尼亚语'=>'est',
        '奥杰布瓦语'=>'oji',
        '奥里亚语'=>'ori',
        '奥罗莫语'=>'orm',
        '波兰语'=>'pl',
        '波斯语'=>'per',
        '布列塔尼语'=>'bre',
        '巴什基尔语'=>'bak',
        '巴斯克语'=>'baq',
        '巴西葡萄牙语'=>'pot',
        '白俄罗斯语'=>'bel',
        '柏柏尔语'=>'ber',
        '邦板牙语'=>'pam',
        '保加利亚语'=>'bul',
        '北方萨米语'=>'sme',
        '北索托语'=>'ped',
        '本巴语'=>'bem',
        '比林语'=>'bli',
        '比斯拉马语'=>'bis',
        '俾路支语'=>'bal',   
        '冰岛语'=>'ice',   
        '波斯尼亚语'=>'bos',
        '博杰普尔语'=>'bho',
        '楚瓦什语'=>'chv',  
        '聪加语'=>'tso',
        '丹麦语'=>'dan',    
        '德语'=>'de',  
        '鞑靼语'=>'tat', 
        '掸语'=>'sha',    
        '德顿语'=>'tet',    
        '迪维希语'=>'div', 
        '低地德语'=>'log',  
        '俄语'=>'ru',
        '法语'=>'fra',   
        '菲律宾语'=>'fil',   
        '芬兰语'=>'fin',
        '梵语'=>'san',   
        '弗留利语'=>'fri',   
        '富拉尼语'=>'ful',
        '法罗语'=>'fao', 
        '盖尔语'=>'gla',   
        '刚果语'=>'kon',   
        '高地索布语'=>'ups',
        '高棉语'=>'hkm',   
        '格陵兰语'=>'kal',  
        '格鲁吉亚语'=>'geo',
        '古吉拉特语'=>'guj',   
        '古希腊语'=>'gra',   
        '古英语'=>'eno',
        '瓜拉尼语'=>'grn',
        '韩语'=>'kor',   
        '荷兰语'=>'nl',   
        '胡帕语'=>'hup',
        '哈卡钦语'=>'hak',               
        '海地语'=>'ht',
        '豪萨语'=>'hau',   
        '黑山语'=>'mot',
        '吉尔吉斯语'=>'kir',   
        '加利西亚语'=>'glg',   
        '加拿大法语'=>'frn',
        '加泰罗尼亚语'=>'cat',   
        '捷克语'=>'cs',
        '卡拜尔语'=>'kab',   
        '卡纳达语'=>'kan',   
        '卡努里语'=>'kau',
        '卡舒比语'=>'kah',   
        '康瓦尔语'=>'cor',   
        '科萨语'=>'xho',
        '科西嘉语'=>'cos',   
        '克里克语'=>'cre',   
        '克里米亚鞑靼语'=>'cri',
        '克林贡语'=>'kli',   
        '克罗地亚语'=>'hrv',   
        '克丘亚语'=>'que',
        '克什米尔语'=>'kas',   
        '孔卡尼语'=>'kok',   
        '库尔德语'=>'kur',
        '拉丁语'=>'lat',   
        '老挝语'=>'lao',   
        '罗马尼亚语'=>'rom',
        '拉特加莱语'=>'lag',   
        '拉脱维亚语'=>'lav',   
        '林堡语'=>'lim',
        '林加拉语'=>'lin',   
        '卢干达语'=>'lug',   
        '卢森堡语'=>'ltz',
        '卢森尼亚语'=>'ruy',   
        '卢旺达语'=>'kin',   
        '立陶宛语'=>'lit',
        '罗曼什语'=>'roh',   
        '罗姆语'=>'ro',   
        '逻辑语'=>'loj',
        '马来语'=>'may',   
        '缅甸语'=>'bur',   
        '马拉地语'=>'mar',
        '马拉加斯语'=>'mg',   
        '马拉雅拉姆语'=>'mal',   
        '马其顿语'=>'mac',
        '马绍尔语'=>'mah',   
        '迈蒂利语'=>'mai',   
        '曼克斯语'=>'glv',
        '毛里求斯克里奥尔语'=>'mau',   
        '毛利语'=>'mao',   
        '孟加拉语'=>'ben',
        '马耳他语'=>'mlt',   
        '苗语'=>'hmn',
        '挪威语'=>'nor',   
        '那不勒斯语'=>'nea',   
        '南恩德贝莱语'=>'nbl',
        '南非荷兰语'=>'afr',   
        '南索托语'=>'sot',   
        '尼泊尔语'=>'nep',
        '葡萄牙语'=>'pt',   
        '旁遮普语'=>'pan',   
        '帕皮阿门托语'=>'pap',
        '普什图语'=>'pus',
        '齐切瓦语'=>'nya',   
        '契维语'=>'twi',   
        '切罗基语'=>'chr',
        '日语'=>'jp',   
        '瑞典语'=>'swe',
        '萨丁尼亚语'=>'srd',   
        '萨摩亚语'=>'sm',   
        '塞尔维亚-克罗地亚语'=>'sec',
        '塞尔维亚语'=>'srp',   
        '桑海语'=>'sol',   
        '僧伽罗语'=>'sin',
        '世界语'=>'epo',   
        '书面挪威语'=>'nob',   
        '斯洛伐克语'=>'sk',
        '斯洛文尼亚语'=>'slo',   
        '斯瓦希里语'=>'swa',           
        '索马里语'=>'som',
        '泰语'=>'th',   
        '土耳其语'=>'tr',   
        '塔吉克语'=>'tgk',
        '泰米尔语'=>'tam',   
        '他加禄语'=>'tgl',   
        '提格利尼亚语'=>'tir',
        '泰卢固语'=>'tel',   
        '突尼斯阿拉伯语'=>'tua',   
        '土库曼语'=>'tuk',
        '乌克兰语'=>'ukr',   
        '瓦隆语'=>'wln',   
        '威尔士语'=>'wel',
        '文达语'=>'ven',   
        '沃洛夫语'=>'wol',   
        '乌尔都语'=>'urd',
        '西班牙语'=>'spa',   
        '希伯来语'=>'heb',   
        '希腊语'=>'el',
        '匈牙利语'=>'hu',   
        '西弗里斯语'=>'fry',   
        '西里西亚语'=>'sil',
        '希利盖农语'=>'hil',   
        '下索布语'=>'los',   
        '夏威夷语'=>'haw',
        '新挪威语'=>'nno',   
        '西非书面语'=>'nqo',   
        '信德语'=>'snd',
        '修纳语'=>'sna',   
        '宿务语'=>'ceb',   
        '叙利亚语'=>'syr',
        '巽他语'=>'sun',
        '英语'=>'en',   
        '印地语'=>'hi',   
        '印尼语'=>'id',
        '意大利语'=>'it',   
        '越南语'=>'vie',   
        '意第绪语'=>'yid',
        '因特语'=>'ina',   
        '亚齐语'=>'ach',   
        '印古什语'=>'ing',
        '伊博语'=>'ibo',   
        '伊多语'=>'ido',   
        '约鲁巴语'=>'yor',
        '亚美尼亚语'=>'arm',   
        '伊努克提图特语'=>'iku',           
        '伊朗语'=>'ir',
        '中文(简体)'=>'zh',   
        '中文(繁体)'=>'cht',   
        '中文(文言文)'=>'wyw',
        '中文(粤语)'=>'yue',   
        '扎扎其语'=>'zaz',   
        '中古法语'=>'frm',
        '祖鲁语'=>'zul',   
        '爪哇语'=>'jav',
    ];
}