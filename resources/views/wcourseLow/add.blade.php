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
		<div class="layui-form"   url="wcourse-low-form" sign="wcourse.form" >
		    <div class="ELEMENT" style="width:80%;height: calc(100vh - 90px);">
		        <div class="layui-form-item">
	                <label class="layui-form-label">课程主图</label>
	                <div class="layui-input-block singleImage" name="course_low_img" width="150px" border_remarks="20px" verify="noNull" mark="course_low_img" nameVerify="课程主图">
	                        
	                </div>
	            </div>

	            <!-- <div class="layui-form-item">
	                <label class="layui-form-label">单视频</label>
	                <div class="layui-input-block singleVideo" name="course_low_video" width="300px" border_remarks="20px" verify="noNull" mark="course_low_video" nameVerify="单视频" url="wcourse-low-video">
	                        
	                </div>
	            </div> -->
				
				<div class="layui-form-item ">
	                <label class="layui-form-label">标识</label>
	                <div class="layui-input-block">
	                    <input type="text" name="w_name" autocomplete="off" class="layui-input layext-text-tool"  placeholder="请输入标识" maxlength="50" lay-verify="noNull" name-verify="标识">
	                </div>
	            </div>
	            <!-- 123 -->
	            <div class="layui-form-item ">
	                <label class="layui-form-label">课程标题</label>
	                
	                <div class="layui-input-block manyJson" name="many" data-class="manyClass" remove="1" >
		                <div class="layui-input-inline">
		                    <span class="layui-btn layui-btn-primary layui-btn-sm layui-border-blue manyJsonButton">新增</span>
		                    <span 
	                        class="manyClass" 
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
	                        ></span>
	                        <span 
	                        class="manyClass" 
	                        data-type="textool" 
	                        data-name="w_name" 
	                        data-max="50" 
	                        data-verify="noNull" 
	                        data-nameVerify="课程名称" 
	                        lay_tips="课程名称" 
	                        ></span>
	                        <span 
	                        class="manyClass" 
	                        data-type="singleVideo" 
	                        data-mark="course_low_video" 
	                        data-name="course_low_video" 
	                        data-width="200px" 
	                        data-remarks="20px" 
	                        data-verify="noNull" 
	                        data-nameVerify="课程视频" 
	                        data-url="wcourse-low-video" 
	                        lay_tips="课程视频"
	                        ></span>
		                </div>
	                </div>
	            </div>
	            <!-- 123 -->
	            <div class="layui-form-item ">
	                <label class="layui-form-label">限制条件</label>
					<div class="layui-input-block manyJson" name="factor" data-class="factorClass" remove="1" >
		                <div class="layui-input-inline">
		                    <span class="layui-btn layui-btn-primary layui-btn-sm layui-border-blue manyJsonButton">新增</span>
		                    <span 
	                        class="factorClass" 
	                        data-type="select" 
	                        data-mark="type" 
	                        data-name="type" 
	                        lay_tips="条件类型" 
	                        filter="layuiFilter" 
	                        data_value="[{'text': '等级',value:1,class:'type1'},{'text': '观看完视频',value:2,class:'type2'}]" 
	                        ></span>
	                        <span 
	                        class="type1" 
	                        data-type="numberInput" 
	                        data-name="factor" 
	                        data-min="1" 
	                        data-max="9999" 
	                        data-step="1" 
	                        data-value="1" 
	                        data-verify="noNull" 
	                        data-nameVerify="等级条件" 
	                        lay_tips="等级条件" 
	                        ></span>
	                        <span 
	                        class="type2" 
	                        data-type="radio" 
	                        data-name="factor" 
	                        data-verify="noNull" 
	                        lay_tips="是否需要观看完上个视频" 
	                        data_value="[{'text': '需要',value:1},{'text': '不需要',value:2}]" 
	                        ></span>
		                </div>
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
<script src="/static/admin/wcourseLow/wcourseLow_add.js"></script>
