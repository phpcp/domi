layui.use(['form','table','unit_call'], function () {
    var form = layui.form,
    table = layui.table,
    $ = layui.$;
    var unit_call = layui.unit_call;
    unit_call.is_404('/admin/wfront-page-list','/admin');
    unit_call.delPower('.Translate','translate.translate');

    unit_call.scrollZoomIn();
    var c_id = $('#List').attr('c_id');
    
    var k_id = $('#List').attr('k_id');
    
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
            {type: 'checkbox', fixed: 'left'},
            {field: 'name', title: '语言名称',  align:'center',fixed:'left',width:150},
            // {field: 'iso', title: '标识',align:'center',width:150},
            // {field: 'route', title: '语言包路径',  align:'center',width:150},
            {field: 'status', title: '状态',width:100,sort:true,  align:'center',templet:function(d){
                var color = d.status == 1?'#1E9FFF':'#FF5722';
                var text = d.status == 1?'正常':'禁用';
                return '<div><span style="color:'+color+'">'+text+'</span></div>';
            }},
            {field: 'text', title: '翻译', align:'center',templet:function(d){
                return '<div><span >'+d.text+'</span></div>';
            }},
            { title: '操作',width:200,  align:'center',fixed:'right',templet:function(d){
                var html = '';
                d.add?html +=  '<a class="layui-btn layui-btn-xs layui-bg-blue" lay-event="translate">手动翻译</a>':'';
                d.translate?html +=  '<a class="layui-btn layui-btn-xs layui-bg-blue" lay-event="baidutranslate">百度翻译</a>':'';
                return html;
            }},
        ]],
        done: done
    });
    //列表操作
    table.on('tool(List)', function(obj){
        var layEvent = obj.event,
            data = obj.data;
        if(layEvent === 'baidutranslate'){
            var index = layer.msg('翻译中，请稍候',{icon: 16,time:false,shade:0.8});
            var dataArray = [];
            dataArray.push(obj.data);

            translateTranslate(dataArray,index);
        }else if( layEvent === 'translate'){
            var array = [];
            array.push(data);
            AddTo(array);
        }
    });
    $("body").on("click",".Refresh",function(){
        table.reload("ListId");
    });
    $("body").on("click",".Translate",function(){
        var checkStatus = table.checkStatus('ListId');
        if( checkStatus.data.length == 0 ){
            layer.msg('请勾选需要翻译的列！');
            return false;
        }
        var dataArray = checkStatus.data;
        var index = layer.msg('翻译中，请稍候',{icon: 16,time:false,shade:0.8});
        translateTranslate(dataArray,index);
    });
    function translateTranslate(dataArray,index='')
    {
        $.ajax({
            url: 'translate-translate',
            type: "post",
            data:{dataArray},
            dataType: "json",
            success:function(res)
            {
                if( index )layer.close(index);
                var resData = res.data;
                for(var i = 0; i < dataArray.length; i++ ){
                    var text = '';
                    for( var x = 0; x < resData[i]['trans_result'].length; x++ ){
                        text += resData[i]['trans_result'][x]['dst']+'\n';
                    }
                    dataArray[i]['text'] = text;
                }
                AddTo(dataArray);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                layer.close(index); 
                var responseText = JSON.parse(XMLHttpRequest.responseText);
                top.layer.msg(responseText.message);
            }
        })
    }
    function AddTo(edit){
        var titleName = '翻译';
        var index = layui.layer.open({
            title : titleName,
            type : 2,
            maxmin:true,
            area: ['80%', '90%'],
            content : "translate-add",
            success : function(layero, index){
                var body = layui.layer.getChildFrame('body', index);
                var html = '';
                for( var i = 0; i < edit.length; i++){
                    html += '<div class="layui-form-item layui-form-text">';
                        html += '<label class="layui-form-label">'+edit[i]['name']+'</label>';
                        html += '<div class="layui-input-block">';
                            html += '<input type="hidden" class="layui-input"  name="translate['+i+'][route]" value="'+edit[i]['route']+'">';
                            html += '<textarea name="translate['+i+'][text]" placeholder="请输入'+edit[i]['name']+'翻译" autocomplete="off" class="layui-textarea layext-text-tool" maxlength="300" lay-verify="noNull" name-verify="'+edit[i]['name']+'翻译">'+edit[i]['text']+'</textarea>';
                        html += '</div>';
                    html += '</div>';
                }
                body.find('.addHtml').append(html);
                body.find('.iso').val(edit[0]['iso']);
                body.find('.key').val(edit[0]['key']);
                body.find('.spanKey').text(edit[0]['key']);
                body.find('.spanText').text(edit[0]['key_text']);
                body.find('.remarks').text(edit[0]['remarks']);
                unit_call.addForm(body);
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
        // $('.key').text(res.record.key);
        // $('.text').text(res.record.text);
        if( res.data.length == 0){
            if( curr != 1){ table.reload("ListId",{page: {curr: curr - 1}})}
        }
    }
});