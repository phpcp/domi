<?php

namespace App\Admin\Controllers;

use App\Models\Wconfig;
use App\Models\Wlanguage;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
class WconfigController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '基础配置';
    public function wconfig_list(Content $content)
    {
        noPjax();
        return $content->title('基础配置')
            ->description('基础配置')
            ->view('wconfig.list');
    }
    public function wconfig_ajax(Request $request)
    {
        $limit = $request->limit;
        $page = ($request->page - 1) * $limit;
        $SortField = empty($request->SortField)?'id':$request->SortField;
        $SortOrder = empty($request->SortOrder)?'desc':$request->SortOrder;
        $Wconfig = Wconfig::orderBy($SortField,$SortOrder)->orderBy('id','desc');
        $count = $Wconfig->count();
        $Wconfig = $Wconfig->offset($page)->limit($limit);
        $WconfigList = $Wconfig->get()->toArray();

        $add = Admin::user()->can('wconfig.add');
        $form = Admin::user()->can('wconfig.form');
        $Wlanguage = Wlanguage::where('status',1)->select( 'id','name','iso')->get()->toArray();
        foreach ($WconfigList as $key => $value) {
            $WconfigList[$key]['add'] = $add;
            $WconfigList[$key]['form'] = $form;

            $many = [];
            foreach ($Wlanguage as $k => $v) {
                $arr = [];
                $lang = DB::table('wconfig_lang_'.$v['iso'].'s')->where('c_id',$value['id'])->first();
                if( !empty($lang)){
                    $arr['wlanguage'] = $v['id'];
                    $arr['wlanguage_title'] = $v['name'];
                    $arr['platform'] = $lang->platform;
                    $arr['group'] = $lang->group;
                    array_push($many,$arr);
                }
            }
            $WconfigList[$key]['platforms'] = $many;
        }
        return return_json(0,'获取成功！',$WconfigList,$count);
    }
    public function wconfig_add()
    {
        return view('wconfig.add')
        ->with('title','基础配置');
    }
    public function wconfig_form(Request $request)
    {
        $data = $request->post();
        $id = $data['ids'];
        unset($data['ids']); 

        $platforms = $data['platforms'];
        $manyDataGroup = dataGroup($platforms,'_remove_');
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
        $Wconfig['whats_app'] = $data['whats_app'];
        $Wconfig['message'] = $data['message'];
        $Wconfig['ins'] = $data['ins'];
        $Wconfig['mail'] = $data['mail'];
        Wconfig::where('id',$id)->update($Wconfig);
        $Wlanguage = Wlanguage::select( 'id','iso')->get()->toArray();
        foreach ($Wlanguage as $key => $value) {
            if( in_array($value['id'],$many_true_id)){
                $langData['platform'] = array_column($many_true, 'platform', 'wlanguage')[$value['id']];
                $langData['group'] = array_column($many_true, 'group', 'wlanguage')[$value['id']];
                $langData['c_id'] = $id;

                $lang = DB::table('wconfig_lang_'.$value['iso'].'s')->where('c_id',$id)->first();
                if( !empty($lang) ){
                    DB::table('wconfig_lang_'.$value['iso'].'s')->where('id',$lang->id)->update($langData);
                }else{
                    DB::table('wconfig_lang_'.$value['iso'].'s')->insert($langData);
                }
               
                $many_false_id = array_merge(array_diff($many_false_id, array($value['id'])));
            }
        }
        foreach ($many_false_id as $key => $value) {
            if( !empty($value)){
                $iso = Wlanguage::where('id',$value)->value('iso');
                $lang = DB::table('wconfig_lang_'.$iso.'s')->where('c_id',$id)->first();
                if( !empty($lang)){
                    DB::table('wconfig_lang_'.$iso.'s')->where('id',$lang->id)->delete();
                }
            }
        }
        return return_json(0,'操作成功！',$id);
    }
}
