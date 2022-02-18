<?php

namespace App\Admin\Controllers;

use App\Models\Wlanguage;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
class WlanguageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '语言设置';

    public function wlanguage_list(Content $content)
    {
        noPjax();
        return $content->title('语言设置')
            ->description('语言设置')
            ->view('wlanguage.list');
    }
    public function wlanguage_ajax(Request $request)
    {
        $limit = $request->limit;
        $page = ($request->page - 1) * $limit;

        $SortField = empty($request->SortField)?'sort':$request->SortField;
        $SortOrder = empty($request->SortOrder)?'asc':$request->SortOrder;

        $wlanguage = Wlanguage::orderBy($SortField,$SortOrder)->orderBy('id','desc')->offset($page)->limit($limit)->get()->toArray();

        $form = Admin::user()->can('wlanguage.form');
        foreach ($wlanguage as $key => $value) {
            $wlanguage[$key]['form'] = $form;
            $wlanguage[$key]['baidu_name_title'] = $value['baidu_name'].'==>'.$value['baidu_iso'];
            $wlanguage[$key]['baidu_name'] = $value['baidu_name'].'==>'.$value['baidu_iso'];
        }
        return return_json(0,'获取成功！',$wlanguage,Wlanguage::count());
    }
    public function wlanguage_add()
    {
        return view('wlanguage.add')
        ->with('title','语言设置');
    }
    public function wlanguage_form(Request $request)
    {
        $data = $request->post();
        if(!empty($data['baidu_name'])){
            $baidu_name = explode('==>',$data['baidu_name']);
            $data['baidu_name'] = $baidu_name[0];
            $data['baidu_iso'] = $baidu_name[1];
        }
        unset($data['baidu_name']);
        if( empty($data['ids'])){
            unset($data['ids']);
            $id = Wlanguage::insertGetId($data);
        }else{
            $id = $data['ids'];
            unset($data['ids']); 
            Wlanguage::where('id',$id)->update($data);
        }
        return return_json(0,'操作成功！',$id);
    }
    public function wlanguage_show(Request $request)
    {
        $data = $request->input('data');
        $wlanguage = Wlanguage::where('status',1)->orderBy('status','asc')->orderBy('sort','asc')->orderBy('id','desc')->select('id','name as title','status')->get()->toArray();
        if( empty($wlanguage)){
            return return_json(1,'没有数据!');
        }else{

            foreach ($wlanguage as $key => $value) {
                if( $value['status'] != 1){
                    $wlanguage[$key]['disabled'] = true;
                }else{
                    if( !empty($data) ){
                        $disabled = explode(',', $data);
                        if( in_array( $value['id'],$disabled ) ){
                            $wlanguage[$key]['disabled'] = true;
                        }
                    }
                }
                $wlanguage[$key]['parentId'] = 0;
                $wlanguage[$key]['checkArr'] = "3";
            }
            $Array[0]['id'] = 0;
            $Array[0]['title'] = '顶级权限';
            $Array[0]['status'] = 1;
            $Array[0]['spread'] = true;
            $Array[0]['parentId'] = 0;
            $Array[0]['children'] = $wlanguage;
            return return_json(0,'操作成功',$Array);
        }
    }
    public function wlanguage_baidu(Request $request)
    {
        $letter = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $array_baidu = array_baidu();
        $Array = [];
        foreach ($letter as $key => $value) {
            $Array[$key]['id'] = $value;
            $Array[$key]['title'] = $value;
            $Array[$key]['spread'] = false;
            $Array[$key]['parentId'] = -1;
            $Array[$key]['children'] = [];
            $getFirstCharter = '';
            $arr = [];
            foreach ($array_baidu as $k => $v) {
                $getFirstCharter = $this->getFirstCharter($k);
                if( empty($getFirstCharter)){
                    if( $k == '鞑靼语'){
                        $getFirstCharter = 'D';
                    }else if ( $k == '俾路支语'){
                        $getFirstCharter = 'B';
                    }else if( $k == '梵语' ){
                        $getFirstCharter = 'F';
                    }else if( $k == '巽他语'){
                        $getFirstCharter = 'X';
                    }
                }
                if( $getFirstCharter == $value ){
                    $arr['id'] = $k.'==>'.$v;
                    $arr['title'] = $k.'==>'.$v;
                    $arr['spread'] = false;
                    $arr['parentId'] = $value;
                    array_push($Array[$key]['children'], $arr);
                    unset($array_baidu[$k]);
                }
            }
        }
        return return_json(0,'操作成功',$Array);
    }
    public function getFirstCharter($str)
    {
        if(empty($str)){return '';}
        $fchar=ord($str{0});
        if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
        $s1 = iconv('UTF-8', 'GBK//TRANSLIT//IGNORE', $str);
        $s2 = iconv('GBK', 'UTF-8//TRANSLIT//IGNORE', $s1);
        $s=$s2==$str?$s1:$str;
        $asc=ord($s{0})*256+ord($s{1})-65536;
        if($asc>=-20319&&$asc<=-20284) return 'A';
        if($asc>=-20283&&$asc<=-19776) return 'B';
        if($asc>=-19775&&$asc<=-19219) return 'C';
        if($asc>=-19218&&$asc<=-18711) return 'D';
        if($asc>=-18710&&$asc<=-18527) return 'E';
        if($asc>=-18526&&$asc<=-18240) return 'F';
        if($asc>=-18239&&$asc<=-17923) return 'G';
        if($asc>=-17922&&$asc<=-17418) return 'H';
        if($asc>=-17417&&$asc<=-16475) return 'J';
        if($asc>=-16474&&$asc<=-16213) return 'K';
        if($asc>=-16212&&$asc<=-15641) return 'L';
        if($asc>=-15640&&$asc<=-15166) return 'M';
        if($asc>=-15165&&$asc<=-14923) return 'N';
        if($asc>=-14922&&$asc<=-14915) return 'O';
        if($asc>=-14914&&$asc<=-14631) return 'P';
        if($asc>=-14630&&$asc<=-14150) return 'Q';
        if($asc>=-14149&&$asc<=-14091) return 'R';
        if($asc>=-14090&&$asc<=-13319) return 'S';
        if($asc>=-13318&&$asc<=-12839) return 'T';
        if($asc>=-12838&&$asc<=-12557) return 'W';
        if($asc>=-12556&&$asc<=-11848) return 'X';
        if($asc>=-11847&&$asc<=-11056) return 'Y';
        if($asc>=-11055&&$asc<=-10247) return 'Z';
        return null;
    }
    
}
