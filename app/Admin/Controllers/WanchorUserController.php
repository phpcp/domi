<?php

namespace App\Admin\Controllers;

use App\Models\WanchorUser;
use App\Models\Wmeeting;
use App\Models\Wagent;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
class WanchorUserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '用户列表';
    public function wanchor_user_list(Content $content)
    {
        noPjax();
        return $content->title('用户列表')
            ->description('用户列表')
            ->view('wanchorUser.list');
    }
    public function wanchor_user_ajax(Request $request)
    {
        $limit = $request->limit;
        $page = ($request->page - 1) * $limit;

        $SortField = empty($request->SortField)?'add_time':$request->SortField;
        $SortOrder = empty($request->SortOrder)?'desc':$request->SortOrder;
       
        $WanchorUser = WanchorUser::orderBy($SortField,$SortOrder)->orderBy('id','desc');
        empty($request->display_name)?'':$WanchorUser->where('display_name','like','%'.$request->display_name.'%');
        empty($request->inv_code)?'':$WanchorUser->where('inv_code','like','%'.$request->inv_code.'%');
        empty($request->is_anchor)?'':$WanchorUser->where('is_anchor','=',$request->is_anchor);
        empty($request->meeting)?'':$WanchorUser->whereIn('meeting',explode(',',$request->meeting));
        $grade = $request->grade;
        !empty($grade)?( $grade != '-1'?$WanchorUser->where('grade','=',$grade):''):( $grade === '0'?$WanchorUser->where('grade','=',$grade):'');
        empty($request->sub_uid)?'':$WanchorUser->whereIn('sub_uid',explode(',',$request->sub_uid));
        empty($request->agent_id)?'':$WanchorUser->whereIn('agent_id',explode(',',$request->agent_id));
        empty($request->status)?'':$WanchorUser->where('status','=',$request->status);

        $count = $WanchorUser->count();
        $WanchorUser = $WanchorUser->offset($page)->limit($limit);
        $WanchorUserList = $WanchorUser->get()->toArray();

        $add = Admin::user()->can('wanchor-user.add');
        $form = Admin::user()->can('wanchor-user.form');

        foreach ($WanchorUserList as $key => $value) {
            $WanchorUserList[$key]['add'] = $add;
            $WanchorUserList[$key]['form'] = $form;
            if( $value['meeting'] != 0 ){
                $WanchorUserList[$key]['meeting_name'] = Wmeeting::where('id','=',$value['meeting'])->value('name');
            }
            if( $value['sub_uid'] != 0 ){
                $WanchorUserList[$key]['sub_name'] = WanchorUser::where('id','=',$value['sub_uid'])->value('display_name');
            }
            if( $value['agent_id'] != 0 ){
                $WanchorUserList[$key]['agent_name'] = Wagent::where('id','=',$value['agent_id'])->value('name');
            }
            $WanchorUserList[$key]['add_time'] = date('Y-m-d H:i:s',$value['add_time']);
            $WanchorUserList[$key]['join_time'] = !empty($value['join_time'])?date('Y-m-d H:i:s',$value['join_time']):'';
        }
        return return_json(0,'获取成功！',$WanchorUserList,$count);
    }
    public function wanchor_user_add()
    {
        return view('wanchorUser.add')
        ->with('title','用户设置');
    }
    public function wanchor_user_form(Request $request)
    {
        $data = $request->post();
        $id = $data['ids'];
        unset($data['ids']); 
        if( empty($data['meeting'])){
            $user['meeting'] = 0;
            $user['is_anchor'] = 2;
        }else{
            $user['meeting'] = $data['meeting'];
            $user['is_anchor'] = 1;
            $user['join_time'] = time();
        }
        $user['sub_uid'] = empty($data['sub_uid'])?0:$data['sub_uid'];
        if( $id == $user['sub_uid']){
            return return_json(1,'您不能选择自己！');
        }
        $user['agent_id'] = empty($data['agent_id'])?0:$data['agent_id'];
        $user['grade'] = $data['grade'];
        
        WanchorUser::where('id',$id)->update($user);
        return return_json(0,'操作成功！',$id);
    }
    public function wanchor_user_show(Request $request)
    {
        $data = $request->input('data');
        $limit = 10;
        $count = WanchorUser::where('status','=',1)->count();
        $pages = ceil($count / $limit);
        for ($i = 0; $i < $pages; $i++) { 
            $arrayRes[$i]['parentId'] = 0;
            $arrayRes[$i]['disabled'] = false;
            $arrayRes[$i]['id'] = '-'.($i + 1);
            $arrayRes[$i]['title'] = '第'.($i + 1).'页';
            $arrayRes[$i]['checkArr']['type'] = 0;
            $arrayRes[$i]['checkArr']['checked'] = 0;
            $page = $i * $limit;
            $WanchorUser = WanchorUser::where('status','=',1)->orderBy('add_time','desc')->orderBy('id','desc');
            $WanchorUser = $WanchorUser->offset($page)->limit($limit);
            $WanchorUserList = $WanchorUser->get()->toArray();
            foreach ($WanchorUserList as $key => $value) {
                $arrayRes[$i]['children'][$key]['parentId'] = '-'.($i + 1);
                $arrayRes[$i]['children'][$key]['disabled'] = false; 
                $arrayRes[$i]['children'][$key]['id'] = $value['id']; 
                $arrayRes[$i]['children'][$key]['title'] = $value['display_name'];
                $arrayRes[$i]['children'][$key]['checkArr']['type'] = 0;
                $arrayRes[$i]['children'][$key]['checkArr']['checked'] = 0;
            }
        }
        return return_json(0,'操作成功！',$arrayRes);
    }
    public function wanchor_user_screen()
    {
        return view('wanchorUser.screen')
        ->with('title','用户筛选');
    }
}
