layui.define(['form'], function(exports){
    var form = layui.form,
    $ = layui.jquery;
    
    var obj = {
        //ajax提交表单
        JKFormSubmitReturn: function (options={},callback) 
        {
            var _this = this;
            var FormId = options.FormId == undefined?'.layui-form':options.FormId;
            var SubmitLayFilter = options.SubmitLayFilter == undefined?'AddForm':options.SubmitLayFilter;

            var formObj = $(FormId);
            form.on("submit("+SubmitLayFilter+")",function(data){
                //弹出loading
                var index = layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});

                var datas = {};
                $.extend(true,datas,options,data.field); 

                delete datas.FormId;
                delete datas.SubmitLayFilter;
                // 实际使用时的提交信息
                $.ajax({
                    url: formObj.attr('url'),
                    type: "post",
                    data:datas,
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(res)
                    {
                        top.layer.msg(res.msg);
                        if( res.code == 0 ){
                            if( res.data.video ){
                                layer.close(index);
                                var video = res.data.video;
                                for( var i = 0; i < video.length; i++ ){
                                    $('.singleVideo_'+video[i]['name']+'_span').attr('data-id',video[i]['id']);
                                    $('.singleVideo_'+video[i]['name']+'_span').click();
                                }
                            }else{
                                layer.close(index); 
                                callback(res);
                            }
                        }else{
                            layer.close(index);
                            callback(res);
                            return false;
                        }
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
            })
        },
        singleVideo:function(VideoName){
            var _this = this;
            var value = $('.singleVideo_'+VideoName+'Id').val();
            if( value != '100' ){
                _this.sleep(1000); 
                _this.singleVideo(VideoName);
            }

            // var name = setInterval(function(){
            //     console.log(123)
            //     var value = $('.singleVideo_'+VideoName+'Id').val();
            //     if( value != '100' ){
            //         clearInterval(name);
            //         console.log(1)
            //     }
            // },400);
        },
        // sleep:function(n) { 
        //     var start = new Date().getTime();
        //     while (true) if (new Date().getTime() - start > n) break;
        // }  
    };
    //输出接口
    exports('communal', obj);
});
