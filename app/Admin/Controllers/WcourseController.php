<?php

namespace App\Admin\Controllers;

use App\Models\Wcourse;
use App\Models\Wlanguage;
use App\Models\WcourseFactor;
use Encore\Admin\Controllers\AdminController;
// use App\Admin\Actions\Post\WcourseLow;
use Encore\Admin\Widgets\Table;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
class WcourseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '课程管理';

    public function wcourse_list(Content $content)
    {
        noPjax();
        return $content->title('课程管理')
            ->description('课程管理')
            ->view('wcourse.list');
    }
    public function wcourse_ajax(Request $request)
    {
        $limit = $request->limit;
        $page = ($request->page - 1) * $limit;

        $SortField = empty($request->SortField)?'sort':$request->SortField;
        $SortOrder = empty($request->SortOrder)?'asc':$request->SortOrder;
       
        $wcourse = Wcourse::orderBy($SortField,$SortOrder)->orderBy('id','desc');

        empty($request->type)?'':$wcourse->where('type','=',$request->type);
        empty($request->status)?'':$wcourse->where('status','=',$request->status);
        empty($request->w_name)?'':$wcourse->where('w_name','like','%'.$request->w_name.'%');
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
                $lang = DB::table('wcourse_lang_'.$v['iso'].'s')->where('c_id',$value['id'])->first();
                if( !empty($lang)){
                    $arr['wlanguage'] = $v['id'];
                    $arr['wlanguage_title'] = $v['name'];
                    $arr['w_name'] = $lang->name;
                    array_push($many,$arr);
                }
            }
            $wcourseList[$key]['many'] = $many;

            $WcourseFactorData = [];
            $WcourseFactorData['w_type'] = 1;
            $WcourseFactorData['w_id'] = $value['id'];
            $WcourseFactor = WcourseFactor::where($WcourseFactorData)->select('type','factor')->get()->toArray();
            $wcourseList[$key]['factor'] = empty($WcourseFactor)?[]:$WcourseFactor;
        }
        return return_json(0,'获取成功！',$wcourseList,$count);
    }
    public function wcourse_add()
    {
        return view('wcourse.add')
        ->with('title','课程管理');
    }

    public function wcourse_form(Request $request)
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

        $wcourse['type'] = $data['type'];
        $wcourse['status'] = $data['status'];
        $wcourse['sort'] = $data['sort'];
        $wcourse['w_name'] = $data['w_name'];

        if( empty($data['course_img']) ){
            return return_json(1,'课程主图不能为空！');
        }
        if( empty($data['ids'])){
            $image_name = base64_upload($data['course_img'],'course_img/');
            $wcourse['course_img'] = 'course_img/'.$image_name;
            $wcourse['created_at'] = date('Y-m-d H:i:s',time());
            $wcourse['updated_at'] = date('Y-m-d H:i:s',time());
            $id = Wcourse::insertGetId($wcourse);
        }else{
            $id = $data['ids'];
            if( $data['course_img'] != 1 ){
                $image_name = base64_upload($data['course_img'],'course_img/');
                $wcourse['course_img'] = 'course_img/'.$image_name;
                $course_img = Wcourse::where('id',$id)->value('course_img');
                $file = '/'.$course_img;
                Storage::disk('admin')->delete($file);
            }
            $wcourse['updated_at'] = date('Y-m-d H:i:s',time());
            Wcourse::where('id',$id)->update($wcourse);
        }
        $Wlanguage = Wlanguage::select( 'id','iso')->get()->toArray();
        foreach ($Wlanguage as $key => $value) {
            if( in_array($value['id'],$many_true_id)){
                $langData['name'] = array_column($many_true, 'w_name', 'wlanguage')[$value['id']];
                $langData['c_id'] = $id;
                $lang = DB::table('wcourse_lang_'.$value['iso'].'s')->where('c_id',$id)->first();
                if( !empty($lang) ){
                    DB::table('wcourse_lang_'.$value['iso'].'s')->where('id',$lang->id)->update($langData);
                }else{
                    DB::table('wcourse_lang_'.$value['iso'].'s')->insert($langData);
                }
                $many_false_id = array_merge(array_diff($many_false_id, array($value['id'])));
            }
        }
        foreach ($many_false_id as $key => $value) {
            if( !empty($value)){
                $iso = Wlanguage::where('id',$value)->value('iso');
                $lang = DB::table('wcourse_lang_'.$iso.'s')->where('c_id',$id)->first();
                if( !empty($lang)){
                    DB::table('wcourse_lang_'.$iso.'s')->where('id',$lang->id)->delete();
                }
            }
        }
        if( !empty( $factor )){
            foreach ($factor_true as $key => $value) {
                $WcourseFactorData = [];
                $WcourseFactorData['type'] = $value['type'];
                $WcourseFactorData['w_type'] = 1;
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
                $WcourseFactorData['w_type'] = 1;
                $WcourseFactorData['w_id'] = $id;
                $WcourseFactor = WcourseFactor::where($WcourseFactorData)->first();
                if( !empty($WcourseFactor)){
                    WcourseFactor::where('id',$WcourseFactor->id)->delete();
                }
            }
        }
        return return_json(0,'操作成功！',$id); 
    }
    public function wcourse_lang_list(Request $request)
    {
        return view('wcourse.lang_list')
        ->with('c_id',$request->c_id)
        ->with('title','课程标题');
    }
    public function wcourse_lang_ajax(Request $request)
    {
        $c_id = $request->c_id;
        if( empty($c_id)){
            return return_json(1,'请使用正规途径进入！');
        }
        if(!is_numeric($c_id)){
            return return_json(1,'请使用正规途径进入！');
        }
        $Wlanguage = Wlanguage::where('status',1)->select( 'id','name','iso')->get()->toArray();
        $many = [];
        foreach ($Wlanguage as $k => $v) {
            $arr = [];
            $lang = DB::table('wcourse_lang_'.$v['iso'].'s')->where('c_id',$c_id)->first();
            if( !empty($lang)){
                $arr['w_name'] = $v['name'];
                $arr['iso'] = $v['iso'];
                $arr['id'] = $lang->id;
                $arr['name'] = $lang->name;
                array_push($many,$arr);
            }
        }
        return return_json(0,'获取成功！',$many,count($many));
    }
    public function wcourse_screen()
    {
        return view('wcourse.screen')
        ->with('title','课程筛选');
    }
}
