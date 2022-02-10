<?php

namespace App\Admin\Controllers;

use App\Models\WcourseLow;
use App\Models\Wlanguage;
use App\Models\Wcourse;
use App\Models\WcourseFactor;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
class WcourseLowController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '课程子列表';
    public function wcourse_low_list(Request $request)
    {
        return view('wcourseLow.list')
        ->with('c_id',$request->c_id)
        ->with('title','课程子列表');
    }
    public function wcourse_low_ajax(Request $request)
    {
        $c_id = $request->c_id;
        if( empty($c_id)){
            return return_json(1,'请使用正规途径进入！');
        }
        if(!is_numeric($c_id)){
            return return_json(1,'请使用正规途径进入！');
        }
        $limit = $request->limit;
        $page = ($request->page - 1) * $limit;
        $SortField = empty($request->SortField)?'sort':$request->SortField;
        $SortOrder = empty($request->SortOrder)?'asc':$request->SortOrder;

        $wcourse = WcourseLow::orderBy($SortField,$SortOrder)->orderBy('id','desc');
        $wcourse = $wcourse->where('c_id','=',$c_id);
        $count = $wcourse->count();
        $wcourse = $wcourse->offset($page)->limit($limit);
        $wcourseList = $wcourse->get()->toArray();
   
        $add = Admin::user()->can('wcourse.add');
        $form = Admin::user()->can('wcourse.form');

        $Wlanguage = Wlanguage::where('status',1)->select( 'id','name','iso')->get()->toArray();

        foreach ($wcourseList as $key => $value) {
            $wcourseList[$key]['add'] = $add;
            $wcourseList[$key]['form'] = $form;
            $many = [];
            foreach ($Wlanguage as $k => $v) {
                $arr = [];
                $lang = DB::table('wcourse_low_lang_'.$v['iso'].'s')->where('cl_id',$value['id'])->first();
                if( !empty($lang)){
                    $arr['wlanguage'] = $v['id'];
                    $arr['wlanguage_title'] = $v['name'];
                    $arr['w_name'] = $lang->name;
                    $arr['course_low_video'] = $lang->course_low_video;
                    array_push($many,$arr);
                }
            }
            $wcourseList[$key]['many'] = $many;

            $WcourseFactorData = [];
            $WcourseFactorData['w_type'] = 2;
            $WcourseFactorData['w_id'] = $value['id'];
            $WcourseFactor = WcourseFactor::where($WcourseFactorData)->select('type','factor')->get()->toArray();
            $wcourseList[$key]['factor'] = empty($WcourseFactor)?[]:$WcourseFactor;
        }
        return return_json(0,'获取成功！',$wcourseList,$count);
    }
    public function wcourse_low_add()
    {
        return view('wcourseLow.add')
        ->with('title','子课程管理');
    }
    public function wcourse_low_form(Request $request)
    {
        $data = $request->post();
        $many = $data['many'];
        $manyDataGroup = dataGroup($many,'_remove_');
        if( empty($manyDataGroup[1])){
            return return_json(1,'请至少选择一种语言！');
        }
        $many_true = $manyDataGroup[1];
        $many_false = empty($manyDataGroup[0])?[]:$manyDataGroup[0];
        $many_true_id = array_column($many_true,'wlanguage');
        $many_false_id = array_unique(array_column($many_false,'wlanguage'));
    
        if (count($many_true_id) != count(array_unique($many_true_id))) { 
            return return_json(1,'课程语言不能重复，请删除多余语言！！！');  
        }
        if( empty($data['c_id'])){
            return return_json(1,'请使用正规途径添加！'); 
        }

        if( !empty($data['factor'])){
            $factor = $data['factor'];
            $factorDataGroup = dataGroup($factor,'_remove_');
            $factor_true = empty($factorDataGroup[1])?[]:$factorDataGroup[1];
            $factor_false = empty($factorDataGroup[0])?[]:$factorDataGroup[0];
            $factor_true_id = array_column($factor_true,'type');
            $factor_false_id = array_unique(array_column($factor_false,'type'));
            if (count($factor_true_id) != count(array_unique($factor_true_id))) { 
                return return_json(1,'限制条件不能重复，请删除多余条件！！！');  
            }
        }

        $wcourse['status'] = $data['status'];
        $wcourse['sort'] = $data['sort'];
        $wcourse['c_id'] = $data['c_id'];
        $wcourse['w_name'] = $data['w_name'];

        if( empty($data['course_low_img']) ){
            return return_json(1,'课程主图不能为空！');
        }
        if( empty($data['ids'])){
            $image_name = base64_upload($data['course_low_img'],'course_low_img/');
            $wcourse['course_low_img'] = 'course_low_img/'.$image_name;
            $wcourse['created_at'] = date('Y-m-d H:i:s',time());
            $wcourse['updated_at'] = date('Y-m-d H:i:s',time());
            $id = WcourseLow::insertGetId($wcourse);
        }else{
            $id = $data['ids'];
            if( $data['course_low_img'] != 1 ){
                $image_name = base64_upload($data['course_low_img'],'course_low_img/');
                $wcourse['course_low_img'] = 'course_low_img/'.$image_name;
                $course_low_img = WcourseLow::where('id',$id)->value('course_low_img');
                $file = '/'.$course_low_img;
                Storage::disk('admin')->delete($file);
            }
            $wcourse['updated_at'] = date('Y-m-d H:i:s',time());
            WcourseLow::where('id',$id)->update($wcourse);
        }
        // $arr['video'][0]['name'] = 'course_low_video';
        // $arr['video'][0]['id'] = '1';
        // return return_json(0,'操作成功！',$arr);
        $arr['video'] = [];
        $Wlanguage = Wlanguage::select( 'id','iso')->get()->toArray();
        foreach ($Wlanguage as $key => $value) {
            if( in_array($value['id'],$many_true_id)){
                $langData['name'] = array_column($many_true, 'w_name', 'wlanguage')[$value['id']];
                $course_low_video = array_column($many_true, 'course_low_video', 'wlanguage')[$value['id']];
                $langData['cl_id'] = $id;
                $lang = DB::table('wcourse_low_lang_'.$value['iso'].'s')->where('cl_id',$id)->first();
                if( !empty($lang) ){
                    DB::table('wcourse_low_lang_'.$value['iso'].'s')->where('id',$lang->id)->update($langData);
                    if( $course_low_video['value'] == 1 ){
                        $file = '/'.$lang->course_low_video;
                        Storage::disk('admin')->delete($file);
                        $video['name'] = $course_low_video['mark'];
                        $video['id'] = $value['id'].'-'.$lang->id;
                        array_push($arr['video'],$video);
                    }
                }else{
                    $lang_id = DB::table('wcourse_low_lang_'.$value['iso'].'s')->insertGetId($langData);
                    $video['name'] = $course_low_video['mark'];
                    $video['id'] = $value['id'].'-'.$lang_id;
                    array_push($arr['video'],$video);
                }
                $many_false_id = array_merge(array_diff($many_false_id, array($value['id'])));
            }
        }

        foreach ($many_false_id as $key => $value) {
            $iso = Wlanguage::where('id',$value)->value('iso');
            if( !empty($iso) ){
                $lang = DB::table('wcourse_low_lang_'.$iso.'s')->where('cl_id',$id)->first();
                if( !empty($lang)){
                    DB::table('wcourse_low_lang_'.$iso.'s')->where('id',$lang->id)->delete();
                    $file = '/'.$lang->course_low_video;
                    Storage::disk('admin')->delete($file);
                }
            }
        }

        if( !empty( $factor )){
            foreach ($factor_true as $key => $value) {
                $WcourseFactorData = [];
                $WcourseFactorData['type'] = $value['type'];
                $WcourseFactorData['w_type'] = 2;
                $WcourseFactorData['w_id'] = $id;
                $WcourseFactor = WcourseFactor::where($WcourseFactorData)->first();
                $WcourseFactorData['factor'] = $value['factor'];
                if( empty($WcourseFactor)){
                    WcourseFactor::insert($WcourseFactorData);
                }else{
                    WcourseFactor::where('id',$WcourseFactor->id)->update($WcourseFactorData);
                }
                $factor_false_id = array_merge(array_diff($factor_false_id, array($value['type'])));
            }
            foreach ($factor_false_id as $key => $value) {
                $WcourseFactorData = [];
                $WcourseFactorData['type'] = $value;
                $WcourseFactorData['w_type'] = 2;
                $WcourseFactorData['w_id'] = $id;
                $WcourseFactor = WcourseFactor::where($WcourseFactorData)->first();
                if( !empty($WcourseFactor)){
                    WcourseFactor::where('id',$WcourseFactor->id)->delete();
                }
            }
        }
        if( !empty($arr['video'])){
            return return_json(0,'操作成功！',$arr); 
        }else{
            return return_json(0,'操作成功！'); 
        }
    }
    public function wcourse_low_video(Request $request)
    {
        $id = $request->post('id');
        $id = explode('-',$id);
        $Wlanguage = Wlanguage::where('id','=',$id[0])->select( 'id','iso')->first()->toArray();
        $iso = $Wlanguage['iso'];
        #实现自定义文件上传
        $file = request()->file('file');
        //获取文件的扩展名
        $name = $file->getClientOriginalExtension();
        //获取文件的绝对路径
        $path = $file->getRealPath();
        //定义新的文件名
        $filename = 'course_low_img/'.date('Ymdhis') .rand(1000,9999). '.' . $name;
        $res = Storage::disk('admin')->put($filename, file_get_contents($path));
        $langData['course_low_video'] = $filename;
        DB::table('wcourse_low_lang_'.$iso.'s')->where('id',$id[1])->update($langData);

        return return_json(0,'操作成功！'); 
    }
    public function wcourse_low_lang_list(Request $request)
    {
        return view('wcourseLow.lang_list')
        ->with('cl_id',$request->cl_id)
        ->with('title','课程标题');
    }
    public function wcourse_low_lang_ajax(Request $request)
    {
        $cl_id = $request->cl_id;
        if( empty($cl_id)){
            return return_json(1,'请使用正规途径进入！');
        }
        if(!is_numeric($cl_id)){
            return return_json(1,'请使用正规途径进入！');
        }
        $Wlanguage = Wlanguage::where('status',1)->select( 'id','name','iso')->get()->toArray();
        $many = [];
        foreach ($Wlanguage as $k => $v) {
            $arr = [];
            $lang = DB::table('wcourse_low_lang_'.$v['iso'].'s')->where('cl_id',$cl_id)->first();
            if( !empty($lang)){
                $arr['w_name'] = $v['name'];
                $arr['iso'] = $v['iso'];
                $arr['id'] = $lang->id;
                $arr['name'] = $lang->name;
                $arr['course_low_video'] = $lang->course_low_video;
                array_push($many,$arr);
            }
        }
        return return_json(0,'获取成功！',$many,count($many));
    }
    protected function deep_in_array($value, $array) {  
        foreach($array as $item) {  
            if(!is_array($item)) {  
                if ($item == $value) { 
                    return true; 
                } else { 
                    continue;  
                } 
            }  
            if(in_array($value, $item)) { 
                return true;     
            } else if($this->deep_in_array($value, $item)) { 
                return true;     
            } 
        }  
        return false;  
    }
}
