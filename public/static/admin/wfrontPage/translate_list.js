layui.use(['form','table','unit_call'], function () {
    var form = layui.form,
    table = layui.table,
    $ = layui.$;
    var unit_call = layui.unit_call;
    unit_call.is_404('/admin/wfront-page-list','/admin');
    unit_call.delPower('.Translate','translate.translate');

    unit_call.scrollZoomIn();
    var c_id = $('#List').attr('c_id');
    if( !c_id ){
        location.reload();
    }
    var k_id = $('#List').attr('k_id');
    if( !k_id ){
        location.reload();
    }
    //菜单列表
    var tableIns = table.render({
        elem: '#List',
        url : 'translate-ajax?c_id='+c_id+'&k_id='+k_id,
        cellMinWidth : 100,
        height : "full-140",
        page : false,
        autoSort:false,
        id : "ListId",
        cols : [[
            {field: 'name', title: '语言名称',  align:'center',fixed:'left',width:150},
            // {field: 'iso', title: '标识',align:'center',width:150},
            // {field: 'route', title: '语言包路径',  align:'center',width:150},
            {field: 'status', title: '状态',width:100,sort:true,  align:'center',templet:function(d){
                var color = d.status == 1?'#1E9FFF':'#FF5722';
                var text = d.status == 1?'正常':'禁用';
                return '<div><span style="color:'+color+'">'+text+'</span></div>';
            }},
            {field: 'text', title: '翻译',edit: 'text', align:'center'},
            { title: '操作',width:150,  align:'center',fixed:'right',templet:function(d){
                var html = '';
                d.translate?html +=  '<a class="layui-btn layui-btn-xs layui-bg-blue" lay-event="translate">翻译</a>':'';
                return html;
            }},
        ]],
        done: done
    });
    table.on('edit(List)', function(obj){ 
        if(!obj.data.form  ){
            layer.msg('权限不足！');
            table.reload("ListId",{done: done})
        }else{
            $.ajax({
                url: 'translate-form',
                type: "post",
                data:obj.data,
                dataType: "json",
                success:function(res)
                {
                    top.layer.msg(res.msg);
                    if( res.code != 0 ){table.reload("ListId",{done: done})} 
                    return false;
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    layer.close(index); 
                    var responseText = JSON.parse(XMLHttpRequest.responseText);
                    top.layer.msg(responseText.message);
                },
                complete: function(XMLHttpRequest, textStatus) {
                    // this; // 调用本次AJAX请求时传递的options参数
                }
            })
        }
    });

    //列表操作
    table.on('tool(List)', function(obj){
        var layEvent = obj.event,
            data = obj.data;
        if(layEvent === 'translate'){
            var index = layer.msg('翻译中，请稍候',{icon: 16,time:false,shade:0.8});
            $.ajax({
                url: 'translate-translate',
                type: "post",
                data:obj.data,
                dataType: "json",
                success:function(res)
                {
                    layer.close(index);
                    if(res.code != 0 ){
                        top.layer.msg(res.msg);
                        return false;
                    }
                    var html = '';
                    html += '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">';
                        html += '<span>KEY：</span><span>'+res.data.key+'</span></br>';
                        html += '<span>翻译语言：</span><span>'+res.data.name+'</span></br>';
                        html += '<span>原文：</span></br>';
                        html += '<span style="white-space:pre;color:red">'+res.data.key_text+'</span></br>';
                        html += '<span>翻译结果：</span></br>';
                        for( var i = 0; i < res.data.trans_result.length; i++ ){
                            html += '<span style="color:red">'+res.data.trans_result[i]['dst']+'</span></br>';
                        }
                        html += '</br>';
                    html += '</div>';
                    layer.open({
                        type: 1
                        ,title: false //不显示标题栏
                        ,closeBtn: false
                        ,area: '500px;'
                        ,shade: 0.8
                        ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                        ,resize: false
                        ,btn: ['确定翻译', '取消']
                        ,btnAlign: 'c'
                        ,moveType: 1 //拖拽模式，0或者1
                        ,content: html
                        ,success: function(layero){},
                        yes: function(index, layero){
                            var text = '';
                            for( var i = 0; i < res.data.trans_result.length; i++ ){
                                text += res.data.trans_result[i]['dst']+'\n';
                            }
                            data.text = text;
                            $.ajax({
                                url: 'translate-form',
                                type: "post",
                                data:data,
                                dataType: "json",
                                success:function(res)
                                {
                                    top.layer.msg(res.msg);
                                    layer.close(index);
                                    if( res.code == 0 ){table.reload("ListId",{done: done})} 
                                    return false;
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    layer.close(index); 
                                    var responseText = JSON.parse(XMLHttpRequest.responseText);
                                    top.layer.msg(responseText.message);
                                },
                                complete: function(XMLHttpRequest, textStatus) {
                                    // this; // 调用本次AJAX请求时传递的options参数
                                }
                            })
                        }
                    });
                    // if( res.code != 0 ){table.reload("ListId",{done: done})} 
                    // return false;
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    layer.close(index); 
                    var responseText = JSON.parse(XMLHttpRequest.responseText);
                    top.layer.msg(responseText.message);
                },
                complete: function(XMLHttpRequest, textStatus) {
                    // this; // 调用本次AJAX请求时传递的options参数
                }
            })
        }
    });
    $("body").on("click",".Refresh",function(){
        table.reload("ListId");
    });
    // $("body").on("click",".AddTo",function(){
    //     AddTo();
    // });
    //添加数据
    // function AddTo(edit){
    //     var titleName = edit?'编辑':'添加';
    //     var index = layui.layer.open({
    //         title : titleName,
    //         type : 2,
    //         maxmin:true,
    //         area: ['40%', '70%'],
    //         content : "wfront-page-add",
    //         success : function(layero, index){
    //             var body = layui.layer.getChildFrame('body', index);

    //             unit_call.addForm(body,edit);
    //             form.render();
    //             body.find(".layui-form").attr('t',2);
    //             setTimeout(function(){
    //                 layui.layer.tips('点击此处返回模块列表', '.layui-layer-setwin .layui-layer-close', {
    //                     tips: 3
    //                 });
    //             },500)
    //         },
    //         end:function(){table.reload("ListId",{done: done})}
    //     })
    // }
    function done(res, curr, count)
    {
        $('.key').text(res.record.key);
        $('.text').text(res.record.text);
        if( res.data.length == 0){
            if( curr != 1){ table.reload("ListId",{page: {curr: curr - 1}})}
        }
    }
});