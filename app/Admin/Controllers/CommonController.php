<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;
class CommonController extends AdminController
{
    //权限验证
    public function prohibit(Request $request)
    {
        $url = $request->post('url');
        $check = Admin::user()->can($url);
        return return_json($check?0:1,$check?'权限获取成功！':'权限获取失败！');
    }
    public function modular_status(Request $request)
    {
    	$id = $request->post('id');
    	$idname = $request->post('idname');
    	$status = $request->post('status');
    	$title = $request->post('title');
    	$zname = $request->post('zname');
    	$bool = DB::table($title)->where($idname,$id)->update([$zname => $status]);
        return return_json(0,'更新成功!');
    }
}
