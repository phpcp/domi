<?php

namespace App\Admin\Controllers;

use App\Models\Wmeeting;
use App\Models\Wcountryz;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
class WmeetingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '工会列表';
    public function wmeeting_list(Content $content)
    {
        noPjax();
        return $content->title('工会列表')
            ->description('工会列表')
            ->view('wmeeting.list');
    }
    public function wmeeting_ajax(Request $request)
    {
        $limit = $request->limit;
        $page = ($request->page - 1) * $limit;
        $SortField = empty($request->SortField)?'sort':$request->SortField;
        $SortOrder = empty($request->SortOrder)?'desc':$request->SortOrder;
        $wmeeting = Wmeeting::orderBy($SortField,$SortOrder)->orderBy('id','desc');
        $count = $wmeeting->count();
        $wmeeting = $wmeeting->offset($page)->limit($limit);
        $wmeetingList = $wmeeting->get()->toArray();

        $add = Admin::user()->can('wmeeting.add');
        $form = Admin::user()->can('wmeeting.form');
        foreach ($wmeetingList as $key => $value) {
            $wmeetingList[$key]['add'] = $add;
            $wmeetingList[$key]['form'] = $form;
            $wmeetingList[$key]['co_name'] = Wcountryz::where('id',$value['co_id'])->value('name');
            $wmeetingList[$key]['add_time'] = date('Y-m-d H:i:s',$value['add_time']);
        }
        return return_json(0,'获取成功！',$wmeetingList,$count);
    }
    public function wmeeting_add()
    {
        return view('wmeeting.add')
        ->with('title','工会管理');
    }
    public function wmeeting_form(Request $request)
    {
        $data = $request->post();
        if( empty($data['ids'])){
            unset($data['ids']);
            $id = Wmeeting::insertGetId($data);
        }else{
            $id = $data['ids'];
            unset($data['ids']); 
            Wmeeting::where('id',$id)->update($data);
        }
        return return_json(0,'操作成功！',$id);
    }
    public function wmeeting_show(Request $request)
    {
        $data = $request->input('data');
        $limit = 10;
        $count = Wmeeting::where('status','=',1)->count();
        $pages = ceil($count / $limit);

        for ($i = 0; $i < $pages; $i++) { 
            $arrayRes[$i]['parentId'] = 0;
            $arrayRes[$i]['disabled'] = false;
            $arrayRes[$i]['id'] = '-'.($i + 1);
            $arrayRes[$i]['title'] = '第'.($i + 1).'页';
            $arrayRes[$i]['checkArr']['type'] = 0;
            $arrayRes[$i]['checkArr']['checked'] = 0;
            $page = $i * $limit;
            $Wmeeting = Wmeeting::where('status','=',1)->orderBy('sort','desc')->orderBy('id','desc');
            $Wmeeting = $Wmeeting->offset($page)->limit($limit);
            $WmeetingList = $Wmeeting->get()->toArray();
            foreach ($WmeetingList as $key => $value) {
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
}
