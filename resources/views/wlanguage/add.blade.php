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
		<div class="layui-form"   url="wlanguage-form" sign="wlanguage.form" >
		    <div class="ELEMENT" style="width:80%;height: calc(100vh - 90px);">
		        <div class="layui-form-item ">
		            <label class="layui-form-label">标识</label>
		            <div class="layui-input-block">
		                <input type="text" name="iso" autocomplete="off" class="layui-input layext-text-tool"  placeholder="请输入标识" maxlength="50" verify="noNull" name-verify="标识">
		            </div>
		        </div>
		        <div class="layui-form-item ">
		            <label class="layui-form-label">包路径</label>
		            <div class="layui-input-block">
		                <input type="text" name="route" autocomplete="off" class="layui-input layext-text-tool"  placeholder="请输入语言包路径" maxlength="50" verify="noNull" name-verify="语言包路径">
		            </div>
		        </div>
		        <div class="layui-form-item ">
		            <label class="layui-form-label">语言名称</label>
		            <div class="layui-input-block">
		                <input type="text" name="name" autocomplete="off" class="layui-input layext-text-tool"  placeholder="请输入语言名称" maxlength="50" verify="noNull" name-verify="语言名称">
		            </div>
		        </div>
		        <div class="layui-form-item">
	                <label class="layui-form-label">百度翻译</label>
	                <div class="layui-input-block tree" mark="baidu_name" name="baidu_name" vallist="baidu_name_title" url="wlanguage-baidu"  verify="noNull" nameVerify="百度翻译" checkbar="2" width="400px" height="90%"  level="1">
	                        
	                </div>
	            </div>
		        <div class="layui-form-item ">
		            <label class="layui-form-label">状态</label>
		            <div class="layui-input-block">
		                <input type="radio" name="status" value="1" title="正常" checked>
		                <input type="radio" name="status" value="2" title="限制" >
		            </div>
		        </div>
		        <div class="layui-form-item ">
		            <label class="layui-form-label">排序</label>
		            <div class="layui-input-block">
		                <input type="text" class="layui-input numberInput" autocomplete="off" name="sort" placeholder="请输入排序" min="1" max="9999" step="10" value="1" verify="noNull" name-verify="排序">
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
<script src="/static/admin/wlanguage/wlanguage_add.js"></script>
