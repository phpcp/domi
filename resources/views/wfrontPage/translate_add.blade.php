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
		<div class="layui-form"   url="translate-form" sign="translate.form" >
		    <div class="ELEMENT" style="width:80%;height: calc(100vh - 90px);">
				<div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">KEY</label>
                    <div class="layui-input-block">
                    	<div class="layui-form-mid layui-word-aux spanKey"></div>
                    </div>
                </div>
				<div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">原文</label>
                    <div class="layui-input-block">
                    	<div class="layui-form-mid layui-word-aux spanText" style="white-space:pre-line;color:red;word-break:break-all;"></div>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">备注</label>
                    <div class="layui-input-block">
                        <div class="layui-form-mid layui-word-aux remarks" style="white-space:pre-line;color:red;word-break:break-all;"></div>
                    </div>
                </div>
				<div class="addHtml">
						
				</div>

		        <input type="hidden" class="layui-input iso"  name="iso">
		        <input type="hidden" class="layui-input key"  name="key">
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
<script src="/static/admin/wfrontPage/wfrontPage_add.js"></script>