<?php

namespace App\Admin\Controllers;

use App\Models\Wagent;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
class WagentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '代理列表';
    public function wagent_list(Content $content)
    {
        noPjax();
        return $content->title('代理列表')
            ->description('代理列表')
            ->view('wagent.list');
    }
    public function wagent_ajax(Request $request)
    {
        $limit = $request->limit;
        $page = ($request->page - 1) * $limit;
        $SortField = empty($request->SortField)?'sort':$request->SortField;
        $SortOrder = empty($request->SortOrder)?'desc':$request->SortOrder;
        $Wagent = Wagent::orderBy($SortField,$SortOrder)->orderBy('id','desc');
        $count = $Wagent->count();
        $Wagent = $Wagent->offset($page)->limit($limit);
        $WagentList = $Wagent->get()->toArray();
        
        $add = Admin::user()->can('wagent.add');
        $form = Admin::user()->can('wagent.form');

        foreach ($WagentList as $key => $value) {
            $WagentList[$key]['add'] = $add;
            $WagentList[$key]['form'] = $form;
        }
        return return_json(0,'获取成功！',$WagentList,$count);
    }
    public function wagent_add()
    {
        return view('wagent.add')
        ->with('title','代理管理');
    }
    public function wagent_form(Request $request)
    {
        $data = $request->post();
        if( empty($data['ids'])){
            unset($data['ids']);
            $id = Wagent::insertGetId($data);
        }else{
            $id = $data['ids'];
            unset($data['ids']); 
            Wagent::where('id',$id)->update($data);
        }
        return return_json(0,'操作成功！',$id);
    }
    public function wagent_show(Request $request)
    {
        $data = $request->input('data');
        $limit = 10;
        $count = Wagent::where('status','=',1)->count();
        $pages = ceil($count / $limit);

        for ($i = 0; $i < $pages; $i++) { 
            $arrayRes[$i]['parentId'] = 0;
            $arrayRes[$i]['disabled'] = false;
            $arrayRes[$i]['id'] = '-'.($i + 1);
            $arrayRes[$i]['title'] = '第'.($i + 1).'页';
            $arrayRes[$i]['checkArr']['type'] = 0;
            $arrayRes[$i]['checkArr']['checked'] = 0;
            $page = $i * $limit;
            $Wagent = Wagent::where('status','=',1)->orderBy('sort','desc')->orderBy('id','desc');
            $Wagent = $Wagent->offset($page)->limit($limit);
            $WagentList = $Wagent->get()->toArray();
            foreach ($WagentList as $key => $value) {
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
