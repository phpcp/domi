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

        foreach ($WcountryzList as $key => $value) {
            $WcountryzList[$key]['add'] = $add;
            $WcountryzList[$key]['form'] = $form;
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
            $arrayRes[$i]['disabled'] = true;
            $arrayRes[$i]['id'] = '-'.($i + 1);
            $arrayRes[$i]['title'] = '第'.($i + 1).'页';
            $page = $i * $limit;
            $Wcountryz = Wcountryz::where('status','=',1)->orderBy('sort','desc')->orderBy('id','desc');
            $Wcountryz = $Wcountryz->offset($page)->limit($limit);
            $WcountryzList = $Wcountryz->get()->toArray();
            foreach ($WcountryzList as $key => $value) {
                $arrayRes[$i]['children'][$key]['parentId'] = '-'.($i + 1);
                $arrayRes[$i]['children'][$key]['disabled'] = false; 
                $arrayRes[$i]['children'][$key]['id'] = $value['id']; 
                $arrayRes[$i]['children'][$key]['title'] = $value['name'];
            }
        }
        return return_json(0,'操作成功！',$arrayRes);
    }
}
