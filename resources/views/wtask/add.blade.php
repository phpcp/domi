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
    <body>
		<div class="layui-form"   url="wtask-form" sign="wtask.form">
		    <div class="ELEMENT" style="width:80%;height: calc(100vh - 90px)">
		        <div class="layui-form-item">
		            <label class="layui-form-label">头像</label>
		            <div class="layui-input-block singleImage" name="admin_img">
		                    
		            </div>
		        </div>
		        <div class="layui-form-item">
		            <label class="layui-form-label">权限组</label>
		            <div class="layui-input-block tree" name="group_id" vallist="group_id_title" url="{:url('flow/group/show_group')}" checkbar="1">
		                    
		            </div>
		        </div>
		        <div class="layui-form-item ">
		            <label class="layui-form-label">用户名</label>
		            <div class="layui-input-block">
		                <input type="text" name="name" autocomplete="off" class="layui-input layext-text-tool"  placeholder="请输入用户名" maxlength="20">
		            </div>
		        </div>
		        <div class="layui-form-item ">
		            <label class="layui-form-label">手机号码</label>
		            <div class="layui-input-block">
		                <input type="text" name="phone" autocomplete="off" class="layui-input layext-text-tool"  placeholder="请输入手机号码（用户登录）" maxlength="11">
		            </div>
		        </div>
		        <div class="layui-form-item ">
		            <label class="layui-form-label">邮箱</label>
		            <div class="layui-input-block">
		                <input type="text" name="email" autocomplete="off" class="layui-input layext-text-tool"  placeholder="请输入邮箱（用户登录）" maxlength="50">
		            </div>
		        </div>
		        <div class="layui-form-item ">
		            <label class="layui-form-label">登录密码</label>
		            <div class="layui-input-block">
		                <input type="text" name="password" autocomplete="off" class="layui-input layext-text-tool"  placeholder="请输入登录密码（编辑时可为空）" maxlength="50"  onfocus="this.type='password'">
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
		                <input type="text" class="layui-input numberInput" autocomplete="off" name="sort" placeholder="请输入排序" min="1" max="9999" step="10" value="9999">
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
<script src="/static/admin/wtask/wtask_add.js"></script>
