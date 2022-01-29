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
}
