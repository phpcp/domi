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
