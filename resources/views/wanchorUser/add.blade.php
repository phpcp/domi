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
    <body style="margin: 15px 15px 15px 15px;background: #f2f2f2;">
		<div class="layui-form"   url="wanchor-user-form" sign="wanchor-user.form" >
		    <div class="ELEMENT" style="width:80%;height: calc(100vh - 90px);">
		        <div class="layui-form-item">
	                <label class="layui-form-label">工会</label>
	                <div class="layui-input-block tree" mark="meeting" name="meeting" vallist="meeting_name" url="wmeeting-show" checkbar="0" width="400px" height="90%"  level="0">
	                        
	                </div>
	            </div>
	            <div class="layui-form-item">
	                <label class="layui-form-label">上级用户</label>
	                <div class="layui-input-block tree" mark="sub_uid" name="sub_uid" vallist="sub_name" url="wanchor-user-show"  checkbar="0" width="400px" height="90%"  level="0">
	                        
	                </div>
	            </div>
	            <div class="layui-form-item">
	                <label class="layui-form-label">代理商</label>
	                <div class="layui-input-block tree" mark="agent_id" name="agent_id" vallist="agent_name" url="wagent-show"  checkbar="0" width="400px" height="90%"  level="0">
	                        
	                </div>
	            </div>
		        <div class="layui-form-item ">
		            <label class="layui-form-label">等级</label>
		            <div class="layui-input-block">
		                <input type="text" class="layui-input numberInput" autocomplete="off" name="grade" placeholder="请输入等级" min="0" max="9999" step="1" value="0" verify="noNull" name-verify="等级">
		            </div>
		        </div>

		      <!--   <div class="layui-form-item ">
		            <label class="layui-form-label">状态</label>
		            <div class="layui-input-block">
		                <input type="radio" name="status" value="1" title="正常" checked>
		                <input type="radio" name="status" value="2" title="限制" >
		            </div>
		        </div> -->
		        <input type="hidden" class="layui-input Id"  name="ids">
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
<script src="/static/admin/wanchorUser/wanchorUser_add.js"></script>
