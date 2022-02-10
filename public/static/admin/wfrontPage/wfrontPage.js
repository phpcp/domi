layui.use(['form','table','unit_call'], function () {
    var form = layui.form,
    table = layui.table,
    $ = layui.$;
    var unit_call = layui.unit_call;

    unit_call.delPower('.AddTo','wfront-page.add');
    unit_call.delPower('.Screen','wfront-page.list');
    unit_call.scrollZoomIn();

    //菜单列表
    var tableIns = table.render({
        elem: '#List',
        url : 'wfront-page-ajax',
        cellMinWidth : 100,
        height : "full-305",
        page : true,
        limits : [1,10,15,20,25],
        limit : 10,
        autoSort:false,
        id : "ListId",
        cols : [[
            {field: 'id', title: 'ID',align:'center',fixed:'left',sort:true, width:100},
            {field: 'name', title: '页面名称',  align:'center',width:200},
            {field: 'iso', title: '页面标识',  align:'center',width:200},
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
            { title: '操作',width:150,  align:'center',fixed:'right',templet:function(d){
                var html = '';
                d.page_field?html +=  '<a class="layui-btn layui-btn-xs layui-bg-blue" lay-event="PageField">页面字段</a>':'';
                // d.add?html +=  '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>':'';
                // d.del?html +=  '<a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>':'';
                return html;
            }},
        ]],
        done: done
    });
    form.on('switch(filter)', function(data){
        var status = data.elem.checked?1:2;
        unit_call.ChangeState('wfront_pages','status',status,'id',data.elem.id,function(res){
            layer.msg(res.msg);
            if( res.code == 0 ){table.reload("ListId",{done: done})} 
        });
    });
    table.on('edit(List)', function(obj){ 
        if(obj.data.form == false ){
            layer.msg('权限不足！');
            table.reload("ListId",{done: done})
        }else{
            unit_call.ChangeState('wfront_pages',obj.field,obj.value,'id',obj.data.id,function(res){
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
        if(layEvent === 'PageField'){
            var titleName = '《'+data.name+'》 -- 页面字段';
            var index = layui.layer.open({
                title : titleName,
                type : 2,
                maxmin:true,
                area: ['90%', '90%'],
                content : "page-field-list?c_id="+data.id,
                success : function(layero, index){
                    var body = layui.layer.getChildFrame('body', index);
                    // body.find("#List").attr('c_id',data.id);
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
    $("body").on("click",".Screen",function(){
        Screen(parent.screenField);
    });
    //添加数据
    function Screen(edit){
        var index = layui.layer.open({
            title : '筛选',
            type : 2,
            maxmin:true,
            area: ['40%', '70%'],
            content : "wfront-page-screen",
            btn: ['确定','清空','取消'],
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
            yes: function(index, layero){
                var body = layui.layer.getChildFrame('body', index);
                body.find('.formDemo').click();
                layer.close(index); 
            },
            btn2: function(index, layero){
                parent.screenField = {};
            },
            end:function(){
                table.reload("ListId",{
                    page: {curr: 1 },
                    where: parent.screenField
                })
            }
        })
    }
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
            content : "wfront-page-add",
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