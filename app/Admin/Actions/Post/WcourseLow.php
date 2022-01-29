<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class WcourseLow extends RowAction
{
    public $name = '课程子列表';

    // public function handle(Model $model)
    // {
    //     return $this->response()->success('Success message.')->refresh();
    // }
    /**
     * @return  string
     */
    public function href()
    {

        return "wcourse-lows?c_id=".$this->getKey();
    }
}