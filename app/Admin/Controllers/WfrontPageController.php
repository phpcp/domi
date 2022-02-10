<?php

namespace App\Admin\Controllers;

use App\Models\WfrontPage;
use App\Models\Wlanguage;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Widgets\Table;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;

use App\Library\Translate;
class WfrontPageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '页面语言';

    public function wfront_page_list(Content $content)
    {
        noPjax();
        return $content->title('页面语言')
            ->description('页面语言')
            ->view('wfrontPage.list');
    }
    public function wfront_page_ajax(Request $request)
    {
        $limit = $request->limit;
        $page = ($request->page - 1) * $limit;
        $SortField = empty($request->SortField)?'sort':$request->SortField;
        $SortOrder = empty($request->SortOrder)?'asc':$request->SortOrder;
        $WfrontPage = WfrontPage::orderBy($SortField,$SortOrder)->orderBy('id','desc');
        empty($request->status)?'':$WfrontPage->where('status','=',$request->status);
        empty($request->name)?'':$WfrontPage->where('name','like','%'.$request->name.'%');
        empty($request->iso)?'':$WfrontPage->where('iso','like','%'.$request->iso.'%');
        $count = $WfrontPage->count();
        $WfrontPage = $WfrontPage->offset($page)->limit($limit);
        $WfrontPageList = $WfrontPage->get()->toArray();

        $add = Admin::user()->can('wfront-page.add');
        $form = Admin::user()->can('wfront-page.form');

        $page_field = Admin::user()->can('page-field.list');
        foreach ($WfrontPageList as $key => $value) {
            $WfrontPageList[$key]['add'] = $add;
            $WfrontPageList[$key]['form'] = $form;
            $WfrontPageList[$key]['page_field'] = $page_field;
        }
        return return_json(0,'获取成功！',$WfrontPageList,$count);
    }
    public function wfront_page_screen()
    {
        return view('wfrontPage.screen')
        ->with('title','筛选');
    }
    public function wfront_page_add()
    {
        return view('wfrontPage.add')
        ->with('title','页面语言');
    }
    public function wfront_page_form(Request $request)
    {
        $data = $request->post();
        $wfront_page['name'] = $data['name'];
        $wfront_page['iso'] = $data['iso'];
        $wfront_page['status'] = $data['status'];
        $wfront_page['sort'] = $data['sort'];
        $WfrontPage = WfrontPage::select('id');
        !empty($data['ids'])?$WfrontPage = $WfrontPage->where('id','<>',$data['ids']):'';
        $name = $WfrontPage->where('name','=',$wfront_page['name'])->first();
        if( !empty($name)){
            return return_json(1,'页面名称禁止重复！'); 
        }
        $iso = $WfrontPage->where('iso','=',$wfront_page['iso'])->first();
        if( !empty($iso)){
            return return_json(1,'页面标识禁止重复！'); 
        }

        if( empty($data['ids'])){
            $id = WfrontPage::insertGetId($wfront_page);
        }else{
            $id = $data['ids'];
            WfrontPage::where('id',$id)->update($wfront_page);
        }
        return return_json(0,'操作成功！',$id); 
    }
    public function page_field_list()
    {
        return view('wfrontPage.page_field')
        ->with('title','页面字段');
    }
    public function page_field_ajax(Request $request)
    {
        $limit = $request->limit;
        $page = ($request->page - 1) * $limit;
        $c_id = $request->c_id;
       
        $WfrontPage = WfrontPage::where('id','=',$c_id)->first()->toArray();
        $path = base_path().'/resources/langpublic/'.$WfrontPage['iso'].'.php';
        if( !file_exists($path) ){
            $myfile = fopen($path, "w");
            $txt = "<?php\n";
            $txt .= "return [\n";
            $txt .= "];";
            fwrite($myfile, $txt);
            fclose($myfile);
        }
        $config = require($path);
        $configRes = [];
        foreach ($config as $key => $value) {
            $configRes[$key]['id'] = $key;
            $configRes[$key]['key'] = $value['key'];
            $configRes[$key]['text'] = $value['text'];
        }
        $count = count($configRes);
        $configList = array_slice($configRes,$page,$limit);
            // $add = Admin::user()->can('wfront-page.add');
        foreach ($configList as $key => $value) {
            //$WfrontPageList[$key]['add'] = $add;
        }
        return return_json(0,'获取成功！',$configList,$count);
    }
    public function page_field_add()
    {
        return view('wfrontPage.page_field_add')
        ->with('title','页面字段');
    }
    public function page_field_form(Request $request)
    {
        $data = $request->post();
        $c_id = $data['c_id'];
        $WfrontPage = WfrontPage::where('id','=',$c_id)->first()->toArray();
        $path = base_path().'/resources/langpublic/'.$WfrontPage['iso'].'.php';

        $config = require($path);
        $found_arr = array_column($config, 'key');
        $found_key = array_search($data['key'], $found_arr);
        if( $found_key !== false ){
            return return_json(1,'KEY 出现重复，请替换！');
        }
        unset($data['c_id']);
        array_unshift($config, $data);
        $myfile = fopen($path, "w");
        $txt = "<?php\n";
        $txt .= "return [\n";
        foreach ($config as $key => $value) {
            $txt .= "    ['key'=>'".$value['key']."','text'=>'".$value['text']."'],\n";
        }
        $txt .= "];";
        if (flock($myfile, LOCK_EX)) {  // 进行排它型锁定
            ftruncate($myfile, 0);      // truncate file
            fwrite($myfile, $txt);
            fflush($myfile);            // flush output before releasing the lock
            flock($myfile, LOCK_UN);    // 释放锁定
        }
        fclose($myfile);
        sleep(3);
        return return_json(0,'操作成功！');
    }
    public function page_field_screen()
    {
        return view('wfrontPage.page_field_screen')
        ->with('title','筛选');
    }
    public function translate_list()
    {
        return view('wfrontPage.translate_list')
        ->with('title','语言翻译');
    }
    public function translate_ajax(Request $request)
    {
        $c_id = $request->c_id;
        $k_id = $request->k_id;

        $WfrontPage = WfrontPage::where('id','=',$c_id)->first()->toArray();
        $path = base_path().'/resources/langpublic/'.$WfrontPage['iso'].'.php';
        $config = require($path);
        $k_res = $config[$k_id];

        $wlanguage = Wlanguage::orderBy('sort','asc')->orderBy('id','desc')->select('id','iso as isos','name','status','route')->get()->toArray();
        $wlanguage_true = [];
        $wlanguage_false = [];
        $form = Admin::user()->can('page-field.list');
        $translate = Admin::user()->can('translate.translate');
        foreach ($wlanguage as $key => $value) {
            $wlanguagePath = base_path().'/resources/lang/'.$value['route'].'/'.$WfrontPage['iso'].'.php';
            if( !file_exists($wlanguagePath) ){
                $myfile = fopen($wlanguagePath, "w");
                $txt = "<?php\n";
                $txt .= "return [\n";
                $txt .= "];";
                fwrite($myfile, $txt);
                fclose($myfile);
            }
            $wlanguageConfig = require($wlanguagePath);
            $found_arr = array_column($wlanguageConfig, 'key');
            $found_key = array_search($k_res['key'], $found_arr);
            if( $found_key === false ){
                $wlanguage_false[$key] = $value;
                $wlanguage_false[$key]['key'] = $k_res['key'];
                $wlanguage_false[$key]['key_text'] = $k_res['text'];
                $wlanguage_false[$key]['text'] = '';
                $wlanguage_false[$key]['iso'] = $WfrontPage['iso'];
                $wlanguage_false[$key]['form'] = $form;
                $wlanguage_false[$key]['translate'] = $translate;
            }else{
                $wlanguage_true[$key] = $value;
                $wlanguage_true[$key]['key'] = $k_res['key'];
                $wlanguage_true[$key]['key_text'] = $k_res['text'];
                $wlanguage_true[$key]['text'] = $wlanguageConfig[$found_key]['text'];
                $wlanguage_true[$key]['iso'] = $WfrontPage['iso'];
                $wlanguage_true[$key]['form'] = $form;
                $wlanguage_true[$key]['translate'] = $translate;
            }
        }
        // 
        foreach ($wlanguage_true as $key => $value) {
            array_push($wlanguage_false,$value);
        }
        
        return return_json(0,'获取成功！',$wlanguage_false,count($wlanguage_false),$k_res);
    }
    public function translate_form(Request $request)
    {
        $data = $request->post();
        $path = base_path().'/resources/lang/'.$data['route'].'/'.$data['iso'].'.php';
        if( !file_exists($path) ){
            $myfile = fopen($path, "w");
            $txt = "<?php\n";
            $txt .= "return [\n";
            $txt .= "];";
            fwrite($myfile, $txt);
            fclose($myfile);
        }
        $config = require($path);
        $found_arr = array_column($config, 'key');
        $found_key = array_search($data['key'], $found_arr);
        if( $found_key === false ){
            $arr['key'] = $data['key'];
            $arr['text'] = $data['text'];
            array_push($config,$arr);
        }else{
            $config[$found_key]['text'] = $data['text'];
        }
        $myfiles = fopen($path, "w");
        $txt = "<?php\n";
        $txt .= "return [\n";
        foreach ($config as $key => $value) {
            $txt .= "    ['key'=>'".$value['key']."','text'=>'".addslashes($value['text'])."'],\n";
        }
        $txt .= "];";
        if (flock($myfiles, LOCK_EX)) {  // 进行排它型锁定
            ftruncate($myfiles, 0);      // truncate file
            fwrite($myfiles, $txt);
            fflush($myfiles);            // flush output before releasing the lock
            flock($myfiles, LOCK_UN);    // 释放锁定
        }
        fclose($myfiles);
        sleep(3);
        return return_json(0,'操作成功！如果列表中没有显示翻译后内容，请点击上方刷新页面！');
    }
    public function translate_translate(Request $request)
    {
        $data = $request->post();
        $res = Translate::translate($data['key_text'],$data['isos']);
        $ResData['name'] = $data['name'];
        $ResData['key'] = $data['key'];
        $ResData['key_text'] = $data['key_text'];
        $ResData['trans_result'] = $res['data'];
        return return_json($res['code'],$res['msg'],$ResData);
    }
}
