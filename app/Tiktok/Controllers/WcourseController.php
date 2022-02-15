<?php

namespace App\Tiktok\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wcourse;
use App\Models\WcourseLow;
use Illuminate\Support\Facades\DB;

class WcourseController 
{
    public $IMGURL = 'http://192.168.31.108:8000/upload/';
    //获取视频主目录
    public function gain_wcourse(Request $request)
    {
        $type = $request->type;
        $lang_iso = $request->lang_iso;

        $limit = $request->limit;
        $page = ($request->page - 1) * $limit;

        $Wcourse = new Wcourse();
        $Wcourse = $Wcourse->where('status','=',1);
        $Wcourse = $Wcourse->where('type','=',$type);
        $Wcourse = $Wcourse->offset($page)->limit($limit);
        $Wcourse = $Wcourse->orderBy('sort','asc');
        $Wcourse = $Wcourse->orderBy('id','desc');
        $Wcourse = $Wcourse->select('id','type','course_img','w_name');
        $Wcourse = $Wcourse->get()->toArray();
        foreach ($Wcourse as $key => $value) {
            $Wcourse[$key]['name'] = DB::table('wcourse_lang_'.$lang_iso.'s')->where('c_id',$value['id'])->value('name');
            $Wcourse[$key]['course_img'] = $this->IMGURL.$value['course_img'];
            $Wcourse[$key]['show'] = 1;
        }
        return return_json(empty($Wcourse)?1:0,'操作成功！',$Wcourse);
    }
    //获取课程子表
    public function gain_wcourse_low(Request $request)
    {
        $c_id = $request->c_id;
        $lang_iso = $request->lang_iso;

        $limit = $request->limit;
        $page = ($request->page - 1) * $limit;
        $WcourseLow = new WcourseLow();
        $WcourseLow = $WcourseLow->where('status','=',1);
        $WcourseLow = $WcourseLow->where('c_id','=',$c_id);
        $WcourseLow = $WcourseLow->orderBy('sort','asc');
        $WcourseLow = $WcourseLow->orderBy('id','desc');
        $WcourseLow = $WcourseLow->offset($page)->limit($limit);
        $WcourseLow = $WcourseLow->select('id','course_low_img','w_name');
        $WcourseLow = $WcourseLow->get()->toArray();
        foreach ($WcourseLow as $key => $value) {
            $lang = DB::table('wcourse_low_lang_'.$lang_iso.'s')->where('cl_id',$value['id'])->select('id','name','course_low_video')->first();
            $WcourseLow[$key]['course_low_img'] = $this->IMGURL.$value['course_low_img'];
            $WcourseLow[$key]['show'] = 1;
            $WcourseLow[$key]['name'] = $lang->name;
            $WcourseLow[$key]['course_low_video'] = $this->IMGURL.$lang->course_low_video;
        }
        return return_json(empty($WcourseLow)?1:0,'操作成功！',$WcourseLow);
    }
}
