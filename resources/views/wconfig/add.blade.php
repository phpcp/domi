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
		<div class="layui-form"   url="wconfig-form" sign="wconfig.form" >
		    <div class="ELEMENT" style="width:80%;height: calc(100vh - 90px);">
				<div class="layui-form-item ">
	                <label class="layui-form-label">WhatsApp</label>
	                <div class="layui-input-block">
	                    <input type="text" name="whats_app" autocomplete="off" class="layui-input layext-text-tool "   placeholder="请输入WhatsApp 账号" maxlength="50" lay-verify="noNull" name-verify="WhatsApp 账号">
	                </div>
	            </div>
	            <div class="layui-form-item ">
	                <label class="layui-form-label">Message</label>
	                <div class="layui-input-block">
	                    <input type="text" name="message" autocomplete="off" class="layui-input layext-text-tool "   placeholder="请输入Message账号" maxlength="50" lay-verify="noNull" name-verify="Message账号">
	                </div>
	            </div>
	            <div class="layui-form-item ">
	                <label class="layui-form-label">Ins</label>
	                <div class="layui-input-block">
	                    <input type="text" name="ins" autocomplete="off" class="layui-input layext-text-tool "   placeholder="请输入Ins账号" maxlength="50" lay-verify="noNull" name-verify="Ins账号">
	                </div>
	            </div>
	            <div class="layui-form-item ">
	                <label class="layui-form-label">Mail</label>
	                <div class="layui-input-block">
	                    <input type="text" name="mail" autocomplete="off" class="layui-input layext-text-tool "   placeholder="请输入Mail账号" maxlength="50" lay-verify="noNull" name-verify="Mail账号">
	                </div>
	            </div>

	            <div class="layui-form-item ">
	                <label class="layui-form-label">介绍</label>
	                <div class="layui-input-block manyJson" name="platforms" data-class="platformClass" remove="1" >
		                <div class="layui-input-inline">
		                    <span class="layui-btn layui-btn-primary layui-btn-sm layui-border-blue manyJsonButton">新增</span>
		                    <span 
	                        class="platformClass" 
	                        data-type="tree" 
	                        data-mark="wlanguage" 
	                        data-name="wlanguage" 
	                        data-vallist="wlanguage_title" 
	                        data-url="wlanguage-show" 
	                        data-verify="noNull" 
	                        data-nameVerify="选择语言" 
	                        data-checkbar="2" 
	                        data-width="400px" 
	                        data-height="90%" 
	                        data-level="1" 
	                        lay_tips="选择语言" 
	                        layui_input = "block" 
	                        ></span>
	                        <span 
	                        class="platformClass" 
	                        data-type="textarea" 
	                        data-name="platform" 
	                        data-max="300" 
	                        data-verify="noNull" 
	                        data-nameVerify="平台介绍" 
	                        lay_tips="平台介绍" 
	                        layui_input = "block" 
	                        ></span>
	                        <span 
	                        class="platformClass" 
	                        data-type="textarea" 
	                        data-name="group" 
	                        data-max="300" 
	                        data-verify="noNull" 
	                        data-nameVerify="工会介绍" 
	                        lay_tips="工会介绍" 
	                        layui_input = "block" 
	                        ></span>
		                </div>
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
<script src="/static/admin/wconfig/wconfig_add.js"></script>
