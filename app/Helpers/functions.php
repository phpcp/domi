<?php
// 应用公共文件
/**
 * 数据表格数据格式
 * @param [type] $msg
 * @param [type] $count
 * @param [type] $data
 * @param integer $code
 */
function return_json(int $code,string $msg,$data = "", int $count = 0)
{
    return response()->json(compact('code', 'msg', 'count', 'data'));
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
