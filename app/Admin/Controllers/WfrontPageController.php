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
    //页面字段
    public function page_field_list(Request $request)
    {
        return view('wfrontPage.page_field')
        ->with('title','页面字段')
        ->with('c_id',$request->c_id);
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
            fclose($myfile);
            $txt = "<?php\n";
            $txt .= "return [\n";
            $txt .= "];";
            file_put_contents($path,$txt);
            // fwrite($myfile, $txt);
        }
        $config = require($path);
        // file_get_contents($path,$txt);
        // $config = file_get_contents($path);
        $configRes = [];
        foreach ($config as $key => $value) {
            $configRes[$key]['id'] = $key;
            $configRes[$key]['key'] = $value['key'];
            $configRes[$key]['text'] = $value['text'];
            $configRes[$key]['remarks'] = $value['remarks'];
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
        $txt = "<?php\n";
        $txt .= "return [\n";
        foreach ($config as $key => $value) {
            $txt .= "    ['key'=>'".$value['key']."','text'=>'".$value['text']."','remarks'=>'".$value['remarks']."'],\n";
        }
        $txt .= "];";
        file_put_contents($path,$txt);
        sleep(3);
        return return_json(0,'操作成功！');
    }
    public function page_field_screen()
    {
        return view('wfrontPage.page_field_screen')
        ->with('title','筛选');
    }
    //语言翻译
    public function translate_list(Request $request)
    {
        return view('wfrontPage.translate_list')
        ->with('title','语言翻译')
        ->with('c_id',$request->c_id)
        ->with('k_id',$request->k_id);
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
        $add = Admin::user()->can('translate.add');
        $form = Admin::user()->can('translate.form');
        $translate = Admin::user()->can('translate.translate');
        foreach ($wlanguage as $key => $value) {
            $wlanguagePath = base_path().'/resources/lang/'.$value['route'].'/'.$WfrontPage['iso'].'.php';
            if( !file_exists($wlanguagePath) ){
                $myfile = fopen($wlanguagePath, "w");
                fclose($myfile);

                $txt = "<?php\n";
                $txt .= "return [\n";
                $txt .= "];";
                file_put_contents($wlanguagePath,$txt);
                // fwrite($myfile, $txt);
            }
            $wlanguageConfig = require($wlanguagePath);
            $found_arr = array_column($wlanguageConfig, 'key');
            $found_key = array_search($k_res['key'], $found_arr);
            if( $found_key === false ){
                $wlanguage_false[$key] = $value;
                $wlanguage_false[$key]['key'] = $k_res['key'];
                $wlanguage_false[$key]['key_text'] = $k_res['text'];
                $wlanguage_false[$key]['remarks'] = $k_res['remarks'];
                $wlanguage_false[$key]['text'] = '';
                $wlanguage_false[$key]['iso'] = $WfrontPage['iso'];
                $wlanguage_false[$key]['form'] = $form;
                $wlanguage_false[$key]['add'] = $add;
                $wlanguage_false[$key]['translate'] = $translate;
            }else{
                $wlanguage_true[$key] = $value;
                $wlanguage_true[$key]['key'] = $k_res['key'];
                $wlanguage_true[$key]['key_text'] = $k_res['text'];
                $wlanguage_true[$key]['remarks'] = $k_res['remarks'];
                $wlanguage_true[$key]['text'] = $wlanguageConfig[$found_key]['text'];
                $wlanguage_true[$key]['iso'] = $WfrontPage['iso'];
                $wlanguage_true[$key]['form'] = $form;
                $wlanguage_true[$key]['add'] = $add;
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

        foreach ($data['translate'] as $key => $value) {
            $path = base_path().'/resources/lang/'.$value['route'].'/'.$data['iso'].'.php';
            if( !file_exists($path) ){
                // $myfile = fopen($path, "w");
                $txt = "<?php\n";
                $txt .= "return [\n";
                $txt .= "];";
                file_put_contents($path,$txt);
                // fwrite($myfile, $txt);
                // fclose($myfile);
            }
            $config = require($path);
            $found_arr = array_column($config, 'key');
            $found_key = array_search($data['key'], $found_arr);
            if( $found_key === false ){
                $arr['key'] = $data['key'];
                $arr['text'] = $value['text'];
                array_push($config,$arr);
            }else{
                $config[$found_key]['text'] = $value['text'];
            }
            $txt = "<?php\n";
            $txt .= "return [\n";
            foreach ($config as $k => $v) {
                $txt .= "    ['key'=>'".$v['key']."','text'=>'".addslashes($v['text'])."'],\n";
            }
            $txt .= "];";
            file_put_contents($path,$txt);
        }
        sleep(3);
        return return_json(0,'操作成功！如果列表中没有显示翻译后内容，请点击上方刷新页面！');
    }
    public function translate_translate(Request $request)
    {
        $data = $request->post();
        $data = $data['dataArray'];
        $ResData = [];
        foreach ($data as $key => $value) {
            $res = Translate::translate($value['key_text'],$value['isos']);
            $ResData[$key]['name'] = $value['name'];
            $ResData[$key]['key'] = $value['key'];
            $ResData[$key]['key_text'] = $value['key_text'];
            $ResData[$key]['trans_result'] = $res['data'];
        }
        return return_json(0,'操作成功！',$ResData);
    }
    public function translate_add(Request $request)
    {
        return view('wfrontPage.translate_add')
        ->with('title','手动翻译');
    }
}
