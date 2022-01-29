layui.use(['form','communal','unit_call'], function () {
    var form = layui.form,
    $ = layui.$;
    var unit_call = layui.unit_call;
    var communal = layui.communal;
    unit_call.is_404('/admin/wmeeting-list','/admin');
   
    unit_call.All();
    communal.JKFormSubmitReturn({},function(res){
        if( res.code == 0 ){
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index); //再执行关闭
        }
    });
});