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
		<div class="layui-form"   url="wcountryz-form" sign="wcountryz.form" >
		    <div class="ELEMENT" style="width:80%;height: calc(100vh - 90px);">
	           
				<div class="layui-form-item ">
	                <label class="layui-form-label">国家名称</label>
	                <div class="layui-input-block">
	                    <input type="text" name="name" autocomplete="off" class="layui-input layext-text-tool "   placeholder="请输入国家名称" maxlength="50" lay-verify="noNull" name-verify="国家名称">
	                </div>
	            </div>
	            <div class="layui-form-item">
	                <label class="layui-form-label">国家代号</label>
	                <div class="layui-input-block tree" mark="code" name="code" vallist="code_title" url="wcountryz-iso"  verify="noNull" nameVerify="国家代号" checkbar="2" width="400px" height="90%"  level="1">
	                        
	                </div>
	            </div>
	            <div class="layui-form-item ">
	                <label class="layui-form-label">状态</label>
	                <div class="layui-input-block">
	                    <input type="radio" name="status" value="1" title="正常" checked>
	                    <input type="radio" name="status" value="2" title="禁用" >
	                </div>
	            </div>
	            <div class="layui-form-item ">
	                <label class="layui-form-label">排序</label>
	                <div class="layui-input-block">
	                    <input type="text" class="layui-input numberInput" autocomplete="off" name="sort" placeholder="请输入排序" min="1" max="9999" step="10" value="9999" verify="noNull" name-verify="排序">
	                </div>
	            </div>
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
<script src="/static/admin/wcountryz/wcountryz_add.js"></script>
