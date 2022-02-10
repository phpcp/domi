layui.use(['form','table','unit_call'], function () {
    var form = layui.form,
    table = layui.table,
    $ = layui.$;
    var unit_call = layui.unit_call;
    unit_call.is_404('/admin/wcourse-list','/admin');

    // unit_call.delPower('.AddTo','wcourse.add');
    unit_call.scrollZoomIn();
    var c_id = $('#List').attr('c_id');
    //菜单列表
    var tableIns = table.render({
        elem: '#List',
        url : 'wcourse-lang-ajax?c_id='+c_id,
        cellMinWidth : 100,
        height : "full-150",
        page : false,
        limits : [1,10,15,20,25],
        limit : 10,
        autoSort:false,
        id : "ListId",
        cols : [[
            // {field: 'id', title: 'ID',align:'center',fixed:'left',sort:true, width:150},
            {field: 'w_name', title: '语言',width:300,align:'center',templet:function(d){
                return  '<div><a class="layui-btn layui-btn-xs layui-bg-blue">'+d.w_name+'</a></div>';
            }},
            {field: 'name', title: '课程标题',  align:'center',width:300},
            // { title: '操作',width:150,  align:'center',fixed:'right',templet:function(d){
            //     var html = '';
            //     html +=  '<a class="layui-btn layui-btn-xs" lay-event="wcourseLowList">课程列表</a>';
            //     d.add?html +=  '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>':'';
            //     d.del?html +=  '<a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>':'';
            //     return html;
            // }},
        ]],
        done: done
    });
    form.on('switch(filter)', function(data){
        var status = data.elem.checked?1:2;
        unit_call.ChangeState('wcourses','status',status,'id',data.elem.id,function(res){
            layer.msg(res.msg);
            if( res.code == 0 ){table.reload("ListId",{done: done})} 
        });
    });
    table.on('edit(List)', function(obj){ 
    	if(obj.data.form == false ){
    		layer.msg('权限不足！');
    		table.reload("ListId",{done: done})
    	}else{
	        unit_call.ChangeState('wcourses',obj.field,obj.value,'id',obj.data.id,function(res){
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
            area: ['40%', '70%'],
            content : "wcourse-add",
            success : function(layero, index){
                var body = layui.layer.getChildFrame('body', index);

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
        if( res.data.length == 0){
            if( curr != 1){ table.reload("ListId",{page: {curr: curr - 1}})}
        }
    }
});