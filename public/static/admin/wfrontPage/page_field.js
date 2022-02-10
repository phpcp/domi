layui.use(['form','table','unit_call'], function () {
    var form = layui.form,
    table = layui.table,
    $ = layui.$;
    var unit_call = layui.unit_call;
    unit_call.is_404('/admin/wfront-page-list','/admin');
    unit_call.delPower('.AddTo','page-field.add');
    unit_call.delPower('.Screen','page-field.list');
    unit_call.scrollZoomIn();

    var c_id = $('#List').attr('c_id');
    //菜单列表
    var tableIns = table.render({
        elem: '#List',
        url : 'page-field-ajax?c_id='+c_id,
        cellMinWidth : 100,
        height : "full-135",
        page : true,
        limits : [1,10,15,20,25],
        limit : 10,
        autoSort:false,
        id : "ListId",
        cols : [[
            {field: 'id', title: '序号',align:'center',fixed:'left',width:120},
            {field: 'key', title: 'KEY',  align:'center',fixed:'left',width:150},
            // {field: 'text', title: '原文',  align:'center'},
            {field: 'text', title: '原文', align:'center',templet:function(d){
                return '<div><span >'+d.text+'</span></div>';
            }},
            {field: 'remarks', title: '备注', align:'center',templet:function(d){
                return '<div ><span >'+d.remarks+'</span></div>';
            }},
            { title: '操作',width:150,  align:'center',fixed:'right',templet:function(d){
                var html = '';
                html +=  '<a class="layui-btn layui-btn-xs layui-bg-blue" lay-event="LanguageTranslation">语言翻译</a>';
                return html;
            }},
        ]],
        done: done
    });
    //列表操作
    table.on('tool(List)', function(obj){
        var layEvent = obj.event,
            data = obj.data;
        if(layEvent === 'LanguageTranslation'){
            var titleName = '《'+data.key+'》 -- 语言翻译';
            var index = layui.layer.open({
                title : titleName,
                type : 2,
                maxmin:true,
                area: ['90%', '90%'],
                content : "translate-list?c_id="+c_id+'&k_id='+data.id,
                success : function(layero, index){
                    var body = layui.layer.getChildFrame('body', index);
                    // body.find("#List").attr('k_id',data.id);
                    // body.find("#List").attr('c_id',1);
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
    $("body").on("click",".Refresh",function(){
        table.reload("ListId");
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
            content : "page-field-screen",
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
            area: ['60%', '80%'],
            content : "page-field-add",
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
            end:function(){
                table.reload("ListId",{done: done});
                layui.layer.msg('如果列表中没有看到添加的KEY，请点击上方刷新页面！');
            }
        })
    }
    function done(res, curr, count)
    {
        //动态监听表头高度变化，冻结行跟着改变高度
        // $(".layui-table-header  tr").resize(function () {
        //     $(".layui-table-header  tr").each(function (index, val) {
        //         $($(".layui-table-fixed .layui-table-header table tr")[index]).height($(val).height());
        //     });
        // });
        //初始化高度，使得冻结行表头高度一致
        // $(".layui-table-header  tr").each(function (index, val) {
        //     $($(".layui-table-fixed .layui-table-header table tr")[index]).height($(val).height());
        // });
        //动态监听表体高度变化，冻结行跟着改变高度
        $(".layui-table-body  tr").resize(function () {
            $(".layui-table-body  tr").each(function (index, val) {
                $($(".layui-table-fixed .layui-table-body table tr")[index]).height($(val).height());
            });
        });
        //初始化高度，使得冻结行表体高度一致
        $(".layui-table-body  tr").each(function (index, val) {
            $($(".layui-table-fixed .layui-table-body table tr")[index]).height($(val).height());
        });

        if( res.data.length == 0){
            if( curr != 1){ table.reload("ListId",{page: {curr: curr - 1}})}
        }
    }
});