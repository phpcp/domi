<?php

namespace App\Tiktok\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wlanguage;

class CurrencyController 
{
    //获取语言列表
    public function gain_language(Request $request)
    {
        $Wlanguage = new Wlanguage();
        $Wlanguage = $Wlanguage->where('status','=',1);
        $Wlanguage = $Wlanguage->orderBy('sort','asc');
        $Wlanguage = $Wlanguage->orderBy('id','desc');
        $Wlanguage = $Wlanguage->select('id','iso','name','route');
        $Wlanguage = $Wlanguage->get()->toArray();
        return return_json(empty($Wlanguage)?1:0,'操作成功！',$Wlanguage);
    }
    //获取页面语言
    public function gain_home_language(Request $request)
    {
        $route = $request->route;
        $home_iso = $request->home_iso;
        $wlanguagePath = base_path().'/resources/lang/'.$route.'/'.$home_iso.'.php';
        if( !file_exists($wlanguagePath) ){
            return return_json(1,'获取失败！',[]);
        }
        $wlanguageConfig = require($wlanguagePath);
        $array = [];
        foreach ($wlanguageConfig as $key => $value) {
            $arr = [];
            $arr[$value['key']] = $value['text'];
            array_push($array,$arr);
        }
        return return_json(0,'获取成功！',$array);
    }
}
