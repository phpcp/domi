<?php

namespace App\Admin\Controllers;

use App\Models\Wtask;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use App\Models\WcourseLow;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Layout\Content;

class WtaskController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '任务管理';
          
    public function wtask_list(Content $content)
    {
        noPjax();
        return $content->title('任务管理')
            ->description('任务管理')
            ->view('wtask.list');
    }
    public function wtask_ajax()
    {
        $data = [
            [
              "id"=> 10000,
              "username"=> "user-0",
              "sex"=> "女",
              "city"=> "城市-0",
              "sign"=> "签名-0",
              "experience"=> 255,
              "logins"=> 24,
              "wealth"=> 82830700,
              "classify"=> "作家",
              "score"=> 57
            ],
            [
              "id"=> 10001,
              "username"=> "user-1",
              "sex"=> "男",
              "city"=> "城市-1",
              "sign"=> "签名-1",
              "experience"=> 884,
              "logins"=> 58,
              "wealth"=> 64928690,
              "classify"=> "词人",
              "score"=> 27
            ]
        ];
        return return_json(0,'获取成功！',$data,2);
    }
    public function wtask_add()
    {
        return view('wtask.add')
        ->with('title','任务管理');
    }
    public function wtask_form()
    {
        dd(1);
    }
}
