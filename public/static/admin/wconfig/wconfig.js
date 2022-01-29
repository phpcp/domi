layui.use(['form','table','unit_call'], function () {
    var form = layui.form,
    table = layui.table,
    $ = layui.$;
    var unit_call = layui.unit_call;

    unit_call.scrollZoomIn();

    //菜单列表
    var tableIns = table.render({
        elem: '#List',
        url : 'wconfig-ajax',
        cellMinWidth : 100,
        height : "full-305",
        page : true,
        limits : [1,10,15,20,25],
        limit : 10,
        autoSort:false,
        id : "ListId",
        cols : [[
            {field: 'id', title: 'ID',align:'center',fixed:'left',sort:true, width:100},
            {field: 'whats_app', title: 'WhatsApp 账号',  edit:'text',align:'center', width:200},
            {field: 'message', title: 'message 账号', edit:'text', align:'center', width:200},
            {field: 'ins', title: 'ins 账号', edit:'text', align:'center', width:200},
            {field: 'mail', title: '邮箱账号', edit:'text', align:'center', width:200},
            {field: 'w_name', title: '语言设置',width:100,align:'center',templet:function(d){
                return  '<div><a class="layui-btn layui-btn-xs layui-bg-blue" lay-event="LanguageSettings">语言设置</a></div>';
            }},
            { title: '操作',width:150,  align:'center',fixed:'right',templet:function(d){
                var html = '';
                d.add?html +=  '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>':'';
                return html;
            }},
        ]],
        done: done
    });
    form.on('switch(filter)', function(data){
        var status = data.elem.checked?1:2;
        unit_call.ChangeState('wconfigs','status',status,'id',data.elem.id,function(res){
            layer.msg(res.msg);
            if( res.code == 0 ){table.reload("ListId",{done: done})} 
        });
    });
    table.on('edit(List)', function(obj){ 
    	if(obj.data.form == false ){
    		layer.msg('权限不足！');
    		table.reload("ListId",{done: done})
    	}else{
	        unit_call.ChangeState('wconfigs',obj.field,obj.value,'id',obj.data.id,function(res){
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
        if(layEvent === 'edit'){
            AddTo(data);
        }else if( layEvent === 'LanguageSettings'){
            var platforms = data.platforms;
            var html = '';
            html += '<table class="layui-table">';
                html += '<colgroup>';
                    html += '<col width="20%">';
                    html += '<col width="35%">';
                    html += '<col width="35%">';
                html += '</colgroup>';
                html += '<thead>';
                    html += '<tr>';
                        html += '<th style="text-align:center"><b>语言</b></th>';
                        html += '<th style="text-align:center"><b>平台介绍</b></th>';
                        html += '<th style="text-align:center"><b>工会介绍</b></th>';
                    html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                for( var i = 0; i < platforms.length; i++ ){
                    html += '<tr>';
                        html += '<td style="text-align:center;word-break:break-all;"><span class="layui-badge layui-bg-blue">'+platforms[i]['wlanguage_title']+'</span></td>';
                        html += '<td style="text-align:center;word-break:break-all;">'+platforms[i]['platform']+'</td>';
                        html += '<td style="text-align:center;word-break:break-all;">'+platforms[i]['group']+'</td>';
                    html += '</tr>';
                }
                html += '</tbody>';
            html += '</table>';
            var titleName = '查看语言设置';
            var index = layui.layer.open({
                title : titleName,
                type : 1,
                maxmin:true,
                area: ['70%', '70%'],
                content : html,
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
    //添加数据
    function AddTo(edit){
        var titleName = edit?'编辑':'添加';
        var index = layui.layer.open({
            title : titleName,
            type : 2,
            maxmin:true,
            area: ['60%', '70%'],
            content : "wconfig-add",
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