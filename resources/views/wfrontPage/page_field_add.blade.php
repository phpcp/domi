<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{{$title}}</title>
        <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
        <link rel="stylesheet" href="/static/public/font-awesome-4.7.0/css/font-awesome.min.css" media="all">
        <!-- <link rel="stylesheet" href="/static/public/font-awesome-4.7.0/css/font-awesome.css" media="all"> -->
        
        <link rel="stylesheet" href="/static/public/public.css" media="all">
        <script src="/static/layui/layui.js" charset="utf-8"></script>
        <script src="/static/public/lay-config.js?v=1.0.4" charset="utf-8"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body style="margin: 15px 15px 15px 15px;background: #f2f2f2;">
		<div class="layui-form"   url="page-field-form" sign="page-field.form" >
		    <div class="ELEMENT" style="width:80%;height: calc(100vh - 90px);">
				<div class="layui-form-item ">
	                <label class="layui-form-label">KEY</label>
	                <div class="layui-input-block">
	                    <input type="text" name="key" autocomplete="off" class="layui-input layext-text-tool "   placeholder="请输入KEY" maxlength="50" lay-verify="noNull" name-verify="KEY">
	                </div>
	            </div>
	            <div class="layui-form-item layui-form-text">
	                <label class="layui-form-label">TEXT</label>
	                <div class="layui-input-block">
	                    <textarea name="text" placeholder="请输入TEXT" autocomplete="off" class="layui-textarea layext-text-tool" maxlength="300" lay-verify="noNull" name-verify="TEXT"></textarea>
	                </div>
	            </div>
		        <input type="hidden" class="layui-input c_id"  name="c_id">
		    </div>
		    <div class="layer-footer PROHIBIT_BUTTON" >
		        <hr style="border-color:#FFFFFF!important">
		        <div class="layui-input-block" >
		            <button class="layui-btn layui-btn-sm" lay-submit="AddForm" id="AddForm" lay-filter="AddForm">立即提交</button>
		        </div>
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
	    unit_call.is_404('/admin/wfront-page-list','/admin');
	   	var c_id = $('.c_id').val();
	    if( !c_id ){
	        location.reload();
	    }
	    unit_call.All();
	    communal.JKFormSubmitReturn({},function(res){
	        if( res.code == 0 ){
	            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
	            parent.layer.close(index); //再执行关闭
	        }
	    });
	});
</script>
