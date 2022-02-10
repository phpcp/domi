layui.use(['form','table','unit_call'], function () {
    var form = layui.form,
    table = layui.table,
    $ = layui.$;
    var unit_call = layui.unit_call;
    unit_call.is_404('/admin/wcourse-list','/admin');
    unit_call.delPower('.AddTo','wcourse.add');
    unit_call.scrollZoomIn();
    var c_id = $('#List').attr('c_id');
    //菜单列表
    var tableIns = table.render({
        elem: '#List',
        url : 'wcourse-low-ajax?c_id='+c_id,
        cellMinWidth : 100,
        height : "full-135",
        page : true,
        limits : [1,10,15,20,25],
        limit : 10,
        autoSort:false,
        id : "ListId",
        cols : [[
            {field: 'id', title: 'ID',align:'center',fixed:'left',sort:true, width:100},
            {field: 'w_name', title: '课程标题',width:200,align:'center',templet:function(d){
                return  '<div><a class="layui-btn layui-btn-xs layui-bg-blue" lay-event="CourseTitle">'+d.w_name+'</a></div>';
            }},
            {field: 'course_low_img', title: '课程主图',  align:'center',width:100,templet:function(d){
                return '<div id="layer-photos-demo-'+d.id+'"><img style="width:40px;cursor:pointer"  src="/upload/'+d.course_low_img+'" alt=""/></div>';
            }},
            {field: 'WcourseFactors', title: '限制条件',width:100,align:'center',templet:function(d){
                if( d.factor.length == 0 ){
                    return '<div></div>';
                }else{
                    return  '<div><a class="layui-btn layui-btn-xs layui-bg-blue" lay-event="WcourseFactors">限制条件</a></div>';
                }
            }},
            {field: 'status', title: '状态',width:100,sort:true,  align:'center',templet:function(d){
                if( d.form ){
                    var checked = d.status == 1?'checked':'';
                    return '<div><input type="checkbox" name="switch" id="'+d.id+'" lay-skin="switch" lay-text="正常|禁用" lay-filter="filter" '+checked+'></div>';
                }else{
                    var color = d.status == 1?'#1E9FFF':'#FF5722';
                    var text = d.status == 1?'正常':'禁用';
                    return '<div><span style="color:'+color+'">'+text+'</span></div>';
                }
            }},
            {field: 'sort', title: '排序',  align:'center',sort:true, width:100},
            {field: 'updated_at', title: '编辑时间',  align:'center',sort:true, width:160},
            {field: 'created_at', title: '添加时间',  align:'center',sort:true, width:160},

            { title: '操作',width:150,  align:'center',fixed:'right',templet:function(d){
                var html = '';
                d.add?html +=  '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>':'';
                d.del?html +=  '<a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>':'';
                return html;
            }},
        ]],
        done: done
    });
    form.on('switch(filter)', function(data){
        var status = data.elem.checked?1:2;
        unit_call.ChangeState('wcourse_lows','status',status,'id',data.elem.id,function(res){
            layer.msg(res.msg);
            if( res.code == 0 ){table.reload("ListId",{done: done})} 
        });
    });
    table.on('edit(List)', function(obj){ 
    	if(obj.data.form == false ){
    		layer.msg('权限不足！');
    		table.reload("ListId",{done: done})
    	}else{
	        unit_call.ChangeState('wcourse_lows',obj.field,obj.value,'id',obj.data.id,function(res){
	            layer.msg(res.msg);
	            if( res.code == 0 ){table.reload("ListId",{done: done})} 
	        });
    	}
	});
    //触发排序事件 
    table.on('sort(List)', function(obj){ 
        table.reload('ListId', {initSort: obj,where: {SortField:obj.field,SortOrder:obj.type}},true);
    });

    //列表操作
    table.on('tool(List)', function(obj){
        var layEvent = obj.event,
            data = obj.data;
        if(layEvent === 'CourseTitle'){
            var titleName = '《'+data.w_name+'》 -- 课程标题';
            var index = layui.layer.open({
                title : titleName,
                type : 2,
                maxmin:true,
                area: ['797px', '90%'],
                content : "wcourse-low-lang-list?cl_id="+data.id,
                success : function(layero, index){
                    var body = layui.layer.getChildFrame('body', index);
                    // body.find("#List").attr('cl_id',data.id);
                    setTimeout(function(){
                        layui.layer.tips('点击此处返回模块列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    },500)
                },
                end:function(){table.reload("ListId",{done: done})}
            })
        }else if( layEvent === 'edit'){
            AddTo(data);
        }else if( layEvent === 'WcourseFactors'){
            if( data.factor.length == 0 ){
                layer.msg('没有限制条件');
                return false;
            }
            var factor = data.factor;
            var html = '';
            html += '<table class="layui-table">';
                html += '<colgroup>';
                    html += '<col width="50%">';
                    html += '<col width="50%">';
                html += '</colgroup>';
                html += '<thead>';
                    html += '<tr>';
                        html += '<th style="text-align:center"><b>条件类型</b></th>';
                        html += '<th style="text-align:center"><b>条件</b></th>';
                    html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                for( var i = 0; i < factor.length; i++ ){
                    html += '<tr>';
                        var type_text = factor[i]['type'] == 1?'等级':'观看完上个视频';
                        html += '<td style="text-align:center">'+type_text+'</td>';
                        if( factor[i]['type'] == 1 ){
                            var factor_span = factor[i]['factor'];
                        }else{
                            var factor_text = factor[i]['factor'] == 1?'需要':'不需要';
                            var factor_color = factor[i]['factor'] == 1?'layui-bg-blue':'';
                            var factor_span = '<span class="layui-badge '+factor_color+'">'+factor_text+'</span>';
                        }
                        html += '<td style="text-align:center">'+factor_span+'</td>';
                    html += '</tr>';
                }
                html += '</tbody>';
            html += '</table>';
            var titleName = '《'+data.w_name+'》 -- 限制条件';
            var index = layui.layer.open({
                title : titleName,
                type : 1,
                maxmin:true,
                area: ['645px', '90%'],
                content : html,
                success : function(layero, index){
                    var body = layui.layer.getChildFrame('body', index);
                    body.find("#List").attr('c_id',data.id);
                    setTimeout(function(){
                        layui.layer.tips('点击此处返回模块列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    },500)
                },
                end:function(){table.reload("ListId",{done: done})}
            })
        }
    });
    $("body").on("click",".AddTo",function(){
        AddTo();
    });
    //添加数据
    function AddTo(edit){
        var titleName = edit?'编辑':'添加';
        var index = layui.layer.open({
            title : titleName,
            type : 2,
            maxmin:true,
            area: ['60%', '80%'],
            content : "wcourse-low-add",
            success : function(layero, index){
                var body = layui.layer.getChildFrame('body', index);
                body.find(".c_id").val(c_id);
                unit_call.addForm(body,edit);
                form.render();
                body.find(".layui-form").attr('t',2);
                setTimeout(function(){
                    layui.layer.tips('点击此处返回模块列表', '.layui-layer-setwin .layui-layer-close', {
                        tips: 3
                    });
                },500)
            },
            end:function(){table.reload("ListId",{done: done})}
        })
    }
    function done(res, curr, count)
    {
        for( var i = 0; i < res.data.length; i++ ){
            layer.photos({
                photos: '#layer-photos-demo-'+res.data[i]['id']
                ,anim: 0
            }); 
        }
        if( res.data.length == 0){
            if( curr != 1){ table.reload("ListId",{page: {curr: curr - 1}})}
        }
    }
});