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
		<div class="layui-form"  sign="wanchor-user.list" >
		    <div class="ELEMENT" style="width:80%;height: calc(100vh - 15px);">
		    	<div class="layui-form-item ">
	                <label class="layui-form-label">TikTok名称</label>
	                <div class="layui-input-block">
	                    <input type="text" name="display_name" autocomplete="off" class="layui-input layext-text-tool display_name"   placeholder="请输入TikTok名称" maxlength="50" >
	                </div>
	            </div>
	            <div class="layui-form-item ">
	                <label class="layui-form-label">邀请码</label>
	                <div class="layui-input-block">
	                    <input type="text" name="inv_code" autocomplete="off" class="layui-input layext-text-tool inv_code"   placeholder="请输入邀请码" maxlength="50" >
	                </div>
	            </div>
	            <div class="layui-form-item ">
	                <label class="layui-form-label">签约主播</label>
	                <div class="layui-input-block">
	                	<input type="radio" class="is_anchor" name="is_anchor" value="" title="未选择" checked>
	                    <input type="radio" class="is_anchor" name="is_anchor" value="1" title="是">
	                    <input type="radio" class="is_anchor" name="is_anchor" value="2" title="否" >
	                </div>
	            </div>
		        <div class="layui-form-item">
	                <label class="layui-form-label">工会</label>
	                <div class="layui-input-block tree" mark="meeting" name="meeting" vallist="meeting_name" url="wmeeting-show" checkbar="1" width="400px" height="90%"  level="1">
	                        
	                </div>
	            </div>
		        <div class="layui-form-item ">
		            <label class="layui-form-label">等级</label>
		            <div class="layui-input-block">
		                <input type="text" class="layui-input numberInput grade" autocomplete="off" name="grade" placeholder="请输入等级" min="-1" max="9999" step="1" value="-1">
		            </div>
		        </div>
	            <div class="layui-form-item">
	                <label class="layui-form-label">上级用户</label>
	                <div class="layui-input-block tree" mark="sub_uid" name="sub_uid" vallist="sub_name" url="wanchor-user-show"  checkbar="1" width="400px" height="90%"  level="1">
	                        
	                </div>
	            </div>
	            <div class="layui-form-item">
	                <label class="layui-form-label">代理商</label>
	                <div class="layui-input-block tree" mark="agent_id" name="agent_id" vallist="agent_name" url="wagent-show"  checkbar="1" width="400px" height="90%"  level="1">
	                        
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
	    unit_call.is_404('/admin/wanchor-user-list','/admin');
	    unit_call.All();

	    form.on("submit(formDemo)",function(data){
	    	var meeting_name = [];
	    	$('.TreemeetingButton').find('.layui-bg-blue').each( function(){
	    		meeting_name.push($(this).text());
	    	})
	    	var sub_name = [];
	    	$('.Treesub_uidButton ').find('.layui-bg-blue').each( function(){
	    		sub_name.push($(this).text());
	    	})
	    	var agent_name = [];
	    	$('.Treeagent_idButton  ').find('.layui-bg-blue').each( function(){
	    		agent_name.push($(this).text());
	    	})
	    	parent.screenField = data.field;
	    	meeting_name.length != 0?parent.screenField['meeting_name'] = meeting_name:'';
	    	sub_name.length != 0?parent.screenField['sub_name'] = sub_name:'';
	    	agent_name.length != 0?parent.screenField['agent_name'] = agent_name:'';
	    })
	});
</script>
