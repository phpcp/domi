layui.use(['form','table','unit_call'], function () {
    var form = layui.form,
    table = layui.table,
    $ = layui.$;
    var unit_call = layui.unit_call;
    unit_call.delPower('.Screen','wanchor-user.list');
    unit_call.scrollZoomIn();

    //菜单列表
    var tableIns = table.render({
        elem: '#List',
        url : 'wanchor-user-ajax',
        cellMinWidth : 100,
        height : "full-305",
        page : true,
        limits : [1,10,15,20,25],
        limit : 10,
        autoSort:false,
        id : "ListId",
        cols : [[
            {field: 'id', title: 'ID',align:'center',fixed:'left',sort:true, width:100},
            {field: 'avatar_url', title: 'TikTok头像',  align:'center',width:100,templet:function(d){
                return '<div id="layer-photos-demo-'+d.id+'"><img style="width:40px;cursor:pointer"  src="'+d.avatar_url+'" alt=""/></div>';
            }},
            {field: 'display_name', title: 'TikTok名称',  align:'center',width:200},
            {field: 'is_anchor', title: '签约主播',width:120,sort:true,  align:'center',templet:function(d){
                var color = d.is_anchor == 1?'layui-bg-blue':'layui-bg-cyan';
                var text = d.is_anchor == 1?'是':'否';
                return '<div><span class="layui-badge '+color+'" >'+text+'</span></div>';
            }},
            {field: 'join_time', title: '工会',width:200,align:'center',sort:true,templet:function(d){
                var html = '';
                html += '<div>';
                if( d.meeting != 0 ){
                    html += '<span class="layui-badge layer_hover layui-bg-blue" data-time="'+d.join_time+'">'+d.meeting_name+'</span>';
                }
                html += '</div>';
                return html;
            }},
            {field: 'grade', title: '等级',width:100,sort:true,  align:'center',templet:function(d){
                var text =  d.grade+' 级';
                return '<div></i><span class="layui-badge  layui-bg-blue" ><i class="fa fa-diamond">  '+text+'</span></div>';
            }},
            {field: 'sub_uid', title: '上级用户',width:120,align:'center',templet:function(d){
                var html = '';
                html += '<div>';
                if( d.sub_uid != 0 ){
                    html += '<span class="layui-badge layui-bg-blue">'+d.sub_name+'</span>';
                }
                html += '</div>';
                return html;
            }},
            {field: 'agent_id', title: '代理商',width:120,align:'center',templet:function(d){
                var html = '';
                html += '<div>';
                if( d.agent_id != 0 ){
                    html += '<span class="layui-badge layui-bg-blue">'+d.agent_name+'</span>';
                }
                html += '</div>';
                return html;
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
            {field: 'inv_code', title: '邀请码',  align:'center',width:150},
            {field: 'add_time', title: '注册时间',  align:'center',sort:true, width:160},
            { title: '操作',width:150,  align:'center',fixed:'right',templet:function(d){
                var html = '';
                d.add?html +=  '<a class="layui-btn layui-btn-xs" lay-event="UserSave">TikTok 用户设置</a>':'';
                // d.del?html +=  '<a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>':'';
                return html;
            }},
        ]],
        done: done
    });

    form.on('switch(filter)', function(data){
        var status = data.elem.checked?1:2;
        unit_call.ChangeState('wanchor_users','status',status,'id',data.elem.id,function(res){
            layer.msg(res.msg);
            if( res.code == 0 ){table.reload("ListId",{done: done})} 
        });
    });
    table.on('edit(List)', function(obj){ 
        if(obj.data.form == false ){
            layer.msg('权限不足！');
            table.reload("ListId",{done: done})
        }else{
            unit_call.ChangeState('wanchor_users',obj.field,obj.value,'id',obj.data.id,function(res){
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
        if( layEvent === 'UserSave'){
            var titleName = 'TikTok 用户：<span style="color:red"> '+data.display_name+' </span>';
            var index = layui.layer.open({
                title : titleName,
                type : 2,
                maxmin:true,
                area: ['40%', '70%'],
                content : "wanchor-user-add",
                success : function(layero, index){
                    var body = layui.layer.getChildFrame('body', index);

                    unit_call.addForm(body,data);
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
            content : "wanchor-user-screen",
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
        var tip_index = 0;
        $(document).on('mouseenter', '.layer_hover', function(){
            var content = $(this).attr('data-time');
            tip_index = layer.tips(content, $(this), { tips: 2,time: 0});
        }).on('mouseleave', '.layer_hover', function(){
            layer.close(tip_index);
        });
    }
});

