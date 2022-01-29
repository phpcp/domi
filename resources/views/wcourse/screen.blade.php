<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{$title}}</title>
        <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
        <link rel="stylesheet" href="/static/public/font-awesome-4.7.0/css/font-awesome.min.css" media="all">
        <link rel="stylesheet" href="/static/public/public.css" media="all">
        <script src="/static/layui/layui.js" charset="utf-8"></script>
        <script src="/static/public/lay-config.js?v=1.0.4" charset="utf-8"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body style="margin: 15px 15px 0px 15px;background: #f2f2f2;">
		<div class="layui-form"  sign="wcourse.list" >
		    <div class="ELEMENT" style="width:80%;height: calc(100vh - 15px);">
	            <div class="layui-form-item ">
	                <label class="layui-form-label">课程类型</label>
	                <div class="layui-input-block">
	                	<input type="radio" name="type" value="" title="未选择" checked>
	                    <input type="radio" name="type" value="1" title="直播技巧">
	                    <input type="radio" name="type" value="2" title="带货技巧" >
	                </div>
	            </div>
		        <div class="layui-form-item ">
		            <label class="layui-form-label">状态</label>
		            <div class="layui-input-block">
		            	<input type="radio" name="status" value="" title="未选择" checked>
		                <input type="radio" name="status" value="1" title="正常" >
		                <input type="radio" name="status" value="2" title="限制" >
		            </div>
		        </div>
		    	<div class="layui-form-item ">
	                <label class="layui-form-label">课程标识</label>
	                <div class="layui-input-block">
	                    <input type="text" name="w_name" autocomplete="off" class="layui-input layext-text-tool w_name"   placeholder="请输入课程标识" maxlength="50" >
	                </div>
	            </div>
		        <span class="formDemo" lay-submit lay-filter="formDemo"></span>
		    </div>
		</div>
    </body>
</html>
<script>
	layui.use(['form','communal','unit_call'], function () {
	    var form = layui.form,
	    $ = layui.$;
	    var unit_call = layui.unit_call;
	    var communal = layui.communal;
	    unit_call.is_404('/admin/wcourse-list','/admin');
	    unit_call.All();

	    form.on("submit(formDemo)",function(data){
	    	parent.screenField = data.field;
	    })
	});
</script>
