<?php

namespace App\Admin\Controllers;

use App\Models\Wcountryz;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
class WcountryzController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '国家列表';
    public function wcountryz_list(Content $content)
    {
        noPjax();
        return $content->title('国家列表')
            ->description('国家列表')
            ->view('wcountryz.list');
    }
    public function wcountryz_ajax(Request $request)
    {
        $limit = $request->limit;
        $page = ($request->page - 1) * $limit;
        $SortField = empty($request->SortField)?'sort':$request->SortField;
        $SortOrder = empty($request->SortOrder)?'desc':$request->SortOrder;
        $Wcountryz = Wcountryz::orderBy($SortField,$SortOrder)->orderBy('id','desc');
        $count = $Wcountryz->count();
        $Wcountryz = $Wcountryz->offset($page)->limit($limit);
        $WcountryzList = $Wcountryz->get()->toArray();

        $add = Admin::user()->can('wcountryz.add');
        $form = Admin::user()->can('wcountryz.form');
        $array_iso = array_iso();
        foreach ($WcountryzList as $key => $value) {
            $WcountryzList[$key]['add'] = $add;
            $WcountryzList[$key]['form'] = $form;
            $WcountryzList[$key]['code_title'] = array_search($value['code'], $array_iso).'==>'.$value['code'];
        }
        return return_json(0,'获取成功！',$WcountryzList,$count);
    }
    public function wcountryz_add()
    {
        return view('wcountryz.add')
        ->with('title','国家管理');
    }
    public function wcountryz_form(Request $request)
    {
        $data = $request->post();
        if( empty($data['ids'])){
            unset($data['ids']);
            $id = Wcountryz::insertGetId($data);
        }else{
            $id = $data['ids'];
            unset($data['ids']); 
            Wcountryz::where('id',$id)->update($data);
        }
        return return_json(0,'操作成功！',$id);
    }
    public function wcountryz_show(Request $request)
    {
        $data = $request->input('data');
        $limit = 10;
        $count = Wcountryz::where('status','=',1)->count();
        $pages = ceil($count / $limit);

        for ($i = 0; $i < $pages; $i++) { 
            $arrayRes[$i]['parentId'] = 0;
            $arrayRes[$i]['disabled'] = false;
            $arrayRes[$i]['id'] = '-'.($i + 1);
            $arrayRes[$i]['title'] = '第'.($i + 1).'页';
            $arrayRes[$i]['checkArr']['type'] = 0;
            $arrayRes[$i]['checkArr']['checked'] = 0;
            $page = $i * $limit;
            $Wcountryz = Wcountryz::where('status','=',1)->orderBy('sort','desc')->orderBy('id','desc');
            $Wcountryz = $Wcountryz->offset($page)->limit($limit);
            $WcountryzList = $Wcountryz->get()->toArray();
            
            foreach ($WcountryzList as $key => $value) {
                $arrayRes[$i]['children'][$key]['parentId'] = '-'.($i + 1);
                $arrayRes[$i]['children'][$key]['disabled'] = false; 
                $arrayRes[$i]['children'][$key]['id'] = $value['id']; 
                $arrayRes[$i]['children'][$key]['title'] = $value['name'];
                $arrayRes[$i]['children'][$key]['checkArr']['type'] = 0;
                $arrayRes[$i]['children'][$key]['checkArr']['checked'] = 0;
            }
        }
        return return_json(0,'操作成功！',$arrayRes);
    }
    public function wlanguage_iso(Request $request)
    {
        $letter = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $array_iso = array_iso();
        $Array = [];
        foreach ($letter as $key => $value) {
            $Array[$key]['id'] = $value;
            $Array[$key]['title'] = $value;
            $Array[$key]['spread'] = false;
            $Array[$key]['parentId'] = -1;
            $Array[$key]['children'] = [];
            $getFirstCharter = '';
            $arr = [];
            foreach ($array_iso as $k => $v) {
                $getFirstCharter = $this->getFirstCharter($k);
                if( empty($getFirstCharter)){
                    if( $k == '斐济'){
                        $getFirstCharter = 'F';
                    }else if ( $k == '瑙鲁'){
                        $getFirstCharter = 'N';
                    }else if( $k == '梵蒂岗' ){
                        $getFirstCharter = 'V';
                    }
                }
                if( $getFirstCharter == $value ){
                    $arr['id'] = $v;
                    $arr['title'] = $k.'==>'.$v;
                    $arr['spread'] = false;
                    $arr['parentId'] = $value;
                    array_push($Array[$key]['children'], $arr);
                    unset($array_iso[$k]);
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
