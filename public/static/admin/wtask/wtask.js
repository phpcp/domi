layui.use(['form','table','unit_call'], function () {
    var form = layui.form,
    table = layui.table,
    $ = layui.$;
    var unit_call = layui.unit_call;

    unit_call.delPower('.AddTo','wtask.add');
    unit_call.scrollZoomIn();

    //菜单列表
    var tableIns = table.render({
        elem: '#List',
        url : 'wtask-ajax',
        cellMinWidth : 100,
        height : "full-305",
        page : true,
        limits : [5,10,15,20,25],
        limit : 10,
        autoSort:false,
        id : "ListId",
        cols : [[
            {field: 'id', title: 'ID',align:'center',fixed:'left',sort:true, width:100},
            // {field: 'name', title: '用户名',align:'center', width:180},
            // {field: 'group_id_title', title: '用户组', minWidth:200, align:'center',templet:function(d){
            //     var html = '<div>';
            //     for( var i = 0; i < d.group_id_title.length; i++ ){
            //         html += '<span class="layui-badge layui-bg-blue" style="margin-right: 5px;">'+d.group_id_title[i]+'</span>';
            //     }
            //     html += '</div>';
            //     return html;
            // }},
            // {field: 'admin_img', title: '头像',  align:'center',width:100,templet:function(d){
            //     return '<div id="layer-photos-demo-'+d.id+'"><img style="width:40px;cursor:pointer"  src="'+d.admin_img+'" alt=""/></div>';
            // }},
            // {field: 'status', title: '状态',width:100,sort:true,  align:'center',templet:function(d){
            //     if( d.form ){
            //         var checked = d.status == 1?'checked':'';
            //         return '<div><input type="checkbox" name="switch" id="'+d.id+'" lay-skin="switch" lay-text="正常|禁用" lay-filter="filter" '+checked+'></div>';
            //     }else{
            //         var color = d.status == 1?'#1E9FFF':'#FF5722';
            //         var text = d.status == 1?'正常':'禁用';
            //         return '<div><span style="color:'+color+'">'+text+'</span></div>';
            //     }
            // }},
            // {field: 'phone', title: '手机号码',  align:'center', width:120},
            // {field: 'email', title: '邮箱',  align:'center', width:180},
            // {field: 'sort', title: '排序',  align:'center',sort:true, width:100},
            // {field: 'update_time', title: '编辑时间',  align:'center',sort:true, width:160},
            // {field: 'add_time', title: '添加时间',  align:'center',sort:true, width:160},

            // { title: '操作',width:120,  align:'center',fixed:'right',templet:function(d){
            //     var html = '';
            //     d.add?html +=  '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>':'';
            //     d.del?html +=  '<a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>':'';
            //     return html;
            // }},
        ]],
        done: done
    });
    form.on('switch(filter)', function(data){
        var status = data.elem.checked?1:2;
        unit_call.ChangeState('admin','status',status,'id',data.elem.id,function(res){
            layer.msg(res.msg);
            if( res.code == 0 ){table.reload("ListId",{done: done})} 
        });
    });

    //触发排序事件 
    table.on('sort(List)', function(obj){ 
        table.reload('ListId', {initSort: obj,where: {SortField:obj.field,SortOrder:obj.type}},true);
    });

    //列表操作
    table.on('tool(List)', function(obj){
        var layEvent = obj.event,
            data = obj.data;
        if(layEvent === 'edit'){ //编辑
            AddTo(data);
        }else if( layEvent === 'del' ){
            layer.confirm('您确定删除账号<span style="color:red"> '+data.name+' </span>吗？', {
                btn: ['删除','取消'] //按钮
            }, function(){
                $.ajax({
                    url: 'admin_del',
                    type: "post",
                    data:{id:data.id},
                    dataType: "json",
                    success:function(res){
                        layer.msg(res.msg);
                        if( res.code == 0 ){
                            table.reload("ListId",{done: done})   
                        }
                    }
                })
            });
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
            content : "wtask-add",
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