layui.define(['form','numinput','textool','numberInput','iconPickerFa','dtree','upload','TMap','element'], function(exports){
    var form = layui.form,
    $ = layui.jquery;
    var numinput = layui.numinput;
    var textool = layui.textool;
    var numberInput = layui.numberInput;
    var iconPickerFa = layui.iconPickerFa;
    var upload = layui.upload;
    var dtree = layui.dtree;
    var TMap = layui.TMap;
    var element = layui.element;
    var obj = {
        All:function()
        {
            var _this = this;
            layer.load(1,{shade:0.8});
            var timeEnd = setInterval(doStuff, 500); 
            function doStuff() { 
                var t = $('.layui-form').attr('t');
                if( t == 2 ){
                    clearInterval(timeEnd);
                    var url = $('.layui-form').attr('sign');
                    $.post(
                        "/admin/prohibit",
                        {
                            url:url
                        },function(res){
                            
                            _this.numinput();           //input 转数字键盘
                            _this.numberInput();        //index 转数组 加加减减
                            _this.textool();            //限制输入框数量
                            _this.iconPickerFa();       //图标选择
                            _this.singleImage();        //单图片
                            _this.singleVideo();

                            _this.manyPic();            //多图片
                            _this.formVerify();         //验证
                            _this.TMapAdd();            //地图
                            if( res.code == 1 ){
                                _this.jurisdiction();
                            }
                            _this.treeAdd();            //树
                            _this.manyJsonButton();
                            _this.deleteMany();
                            _this.layThis();
                            _this.layuiFilter();
                            form.render();
                            layer.closeAll('loading');
                        },
                    'json');
                }
            } 
        },
        manyJsonButton:function()
        {
            var _this = this;
            $(document).on("click", ".manyJsonButton", function (e) {
                var one = $(this).parent().parent();
                var body = $('body');
                _this.manyJsonHtml(one,body);
            });
        },
        manyJsonHtml:function(one,body,data={})
        {
            var _this = this;
            var num = one.parent().children('div').length - 1;
            var name = one.attr('name')+'['+num+']';
            var dataClass = one.attr('data-class');
            var remove = one.attr('remove');
            remove = remove == undefined?1:remove;
            var options = {
                num:num,
                name:name,
                dataClass:dataClass,
                remove:remove
            }
            var html = '';
            html += '<div class="layui-input-block" style="margin-bottom: 10px;" _remove_="1" num="'+num+'">';
                var divList = $(body).find('.'+dataClass);
                divList.each( function(){
                    html += _this.manyJsonHtmlTwo(this,body,options,data);
                })

                if( remove == 1 ){
                    html += '<div class="layui-input-inline " style="float:none;margin-bottom: 10px;width:50px"> ';
                        html += '<input type="hidden" class="layui-input "  name="'+name+'[_remove_]" value="1">';
                        html += '<span class="layui-btn layui-btn-primary layui-btn-sm layui-border-red deleteMany">移除</span>';
                    html += '</div>';
                }
            html += '</div>';
            one.before(html);
            _this.singleImage();        //单图片
            _this.singleVideo();
            _this.numinput();           //input 转数字键盘
            _this.numberInput();        //index 转数组 加加减减
            _this.textool();            //限制输入框数量
            // _this.iconPickerFa();       //图标选择
            _this.treeAdd();            //树
            form.render();
        },
        manyJsonHtmlTwo:function(obj,body,opt,data={})
        {
            var _this = this;
            var dataType =  $(obj).attr('data-type');
            var dataNameLog = $(obj).attr('data-name');
            var dataName =  opt.name+'['+dataNameLog+']';
            var dataVerify =  $(obj).attr('data-verify');
            var dataNameVerify =  $(obj).attr('data-nameVerify');
            var lay_tips = $(obj).attr('lay_tips');
            var dataClass = $(obj).attr('class')+'_div';
            var layui_input = $(obj).attr('layui_input');
            layui_input = $(obj).attr('layui_input')?layui_input:'inline';
            var layer_style = layui_input == 'inline'?'float:none;':'margin-left:0px;';
            var html = '';
            switch(dataType) {
                case 'singleImage':
                    var dataMark =  $(obj).attr('data-mark')+'_'+opt.num;
                    var dataWidth =  $(obj).attr('data-width')?$(obj).attr('data-width'):'100px';
                    dataWidths = dataWidth.replace("px", "");
                    dataWidths = parseInt(dataWidths) + 11;
                    var dataRemarks =  $(obj).attr('data-remarks')?$(obj).attr('data-remarks'):'20px';
                    html += '<div class="layui-input-'+layui_input+' '+dataClass+'" style="'+layer_style+'margin-bottom: 10px;width:'+dataWidths+'px">';
                        html += '<div class="singleImage"  mark="'+dataMark+'" name="'+dataName+'" width="'+dataWidth+'" remarks="'+dataRemarks+'" verify="'+dataVerify+'" nameVerify="'+dataNameVerify+'">';
                            var options = {};
                            options = {
                                'type':'singleImage',
                                'mark':dataMark,
                                'name':dataName,
                                'width':dataWidth,
                                'verify':dataVerify,
                                'nameVerify':dataNameVerify,
                                'remarks':dataRemarks,
                                'imageUrl':data[dataNameLog]?'/upload/'+data[dataNameLog]:'/images/logo.png',
                                'value':data[dataNameLog]?'1':'',
                                'lay_tips':lay_tips
                            };
                            html += _this.addHtml(options);
                        html += '</div>';
                    html += '</div>';
                    return html;
                break;
                case 'singleVideo':
                    var dataMark =  $(obj).attr('data-mark')+'_'+opt.num;
                    var dataWidth =  $(obj).attr('data-width')?$(obj).attr('data-width'):'100px';
                    dataWidths = dataWidth.replace("px", "");
                    dataWidths = parseInt(dataWidths) + 11;
                    var dataUrl =  $(obj).attr('data-url');
                    var dataRemarks =  $(obj).attr('data-remarks')?$(obj).attr('data-remarks'):'20px';
                    html += '<div class="layui-input-'+layui_input+' '+dataClass+'" style="'+layer_style+'margin-bottom: 10px;width:'+dataWidths+'px">';
                        html += '<div class="singleVideo" url="'+dataUrl+'" mark="'+dataMark+'" name="'+dataName+'" width="'+dataWidth+'" remarks="'+dataRemarks+'" verify="'+dataVerify+'" nameVerify="'+dataNameVerify+'">';

                            var options = {};
                            options = {
                                'type':'singleVideo',
                                'mark':dataMark,
                                'name':dataName,
                                'width':dataWidth,
                                'verify':dataVerify,
                                'nameVerify':dataNameVerify,
                                'remarks':dataRemarks,
                                'videoUrl':!data[dataNameLog]?'/images/video.mp4':'/upload/'+data[dataNameLog],
                                'value':!data[dataNameLog]?'':'2',
                                'lay_tips':lay_tips
                            };
                            html += _this.addHtml(options);
                        html += '</div>';
                    html += '</div>';
                    return html;
                break;
                case 'input-number':
                    var layer_hover = lay_tips?'layer_hover':'';
                    var lay_tips = lay_tips?'lay_tips='+lay_tips:'';
                    var dataMin =  $(obj).attr('data-min');
                    var dataMax =  $(obj).attr('data-max');
                    var dataStep =  $(obj).attr('data-step');
                    var dataPrec =  $(obj).attr('data-prec');
                    var dataValue =  data[dataNameLog]?data[dataNameLog]:$(obj).attr('data-value');
                    var dataDefaultPrec =  $(obj).attr('data-defaultPrec');
                    html += '<div class="layui-input-'+layui_input+' '+dataClass+'" style="'+layer_style+'margin-bottom: 10px;">';
                        html += '<input type="text" '+lay_tips+' class="layui-input layui-input-number '+layer_hover+'" autocomplete="off" name="'+dataName+'" placeholder="请输入排序" min="'+dataMin+'" max="'+dataMax+'" step="'+dataStep+'" data-prec="'+dataPrec+'" value="'+dataValue+'" defaultPrec="'+dataDefaultPrec+'" >';
                    html += '</div>';
                    return html;
                break;
                case 'numberInput':
                    var dataMin =  $(obj).attr('data-min');
                    var dataMax =  $(obj).attr('data-max');
                    var dataStep =  $(obj).attr('data-step');
                    var layer_hover = lay_tips?'layer_hover':'';
                    var lay_tips = lay_tips?'lay_tips='+lay_tips:'';
                    var dataValue =  data[dataNameLog]?data[dataNameLog]:$(obj).attr('data-value');
                    html += '<div class="layui-input-'+layui_input+' '+dataClass+'" style="'+layer_style+'margin-bottom: 10px;">';
                        html += '<input type="text" class="layui-input numberInput '+layer_hover+'" '+lay_tips+' autocomplete="off" name="'+dataName+'" placeholder="请输入排序" min="'+dataMin+'" max="'+dataMax+'" step="'+dataStep+'" value="'+dataValue+'">';
                    html += '</div>';
                    return html;
                break;
                case 'textool':
                    var layer_hover = lay_tips?'layer_hover':'';
                    var lay_tips = lay_tips?'lay_tips='+lay_tips:'';
                    var dataMax =  $(obj).attr('data-max');
                    var dataValue =  data[dataNameLog]?data[dataNameLog]:'';
                    html += '<div class="layui-input-'+layui_input+' '+layer_hover+' '+dataClass+'" '+lay_tips+' style="'+layer_style+'margin-bottom: 10px;">';
                        html += '<input type="text" name="'+dataName+'" value="'+dataValue+'" autocomplete="off" class="layui-input layext-text-tool"  placeholder="请输入'+dataNameVerify+'" maxlength="'+dataMax+'" lay-verify="'+dataVerify+'" name-verify="'+dataNameVerify+'">';
                    html += '</div>';
                    return html;
                break;
                case 'iconPickerFa':
                    var dataValue =  data[dataNameLog]?data[dataNameLog]:$(obj).attr('data-value');
                    html += '<div class="layui-input-'+layui_input+' '+dataClass+'" style="'+layer_style+'margin-bottom: 10px;">';
                        html += '<input type="text" name="'+dataName+'" value="'+dataValue+'" lay-filter="icon" class="hide iconPicker icon">';
                    html += '</div>';
                    return html;
                break;
                case 'tree':
                    var dataMark =  $(obj).attr('data-mark')+'_'+opt.num;
                    var dataUrl =  $(obj).attr('data-url');
                    var checkbar =  $(obj).attr('data-checkbar');
                    var width =  $(obj).attr('data-width')?$(obj).attr('data-width'):'400px';
                    var height =  $(obj).attr('data-height')?$(obj).attr('data-height'):'90%';
                    var level =  $(obj).attr('data-level')?$(obj).attr('data-level'):'0';
                    var vallist =  $(obj).attr('data-vallist')?$(obj).attr('data-vallist'):'0';
                    
                    html += '<div class="layui-input-'+layui_input+' '+dataClass+'" style="'+layer_style+'margin-bottom: 10px;">';
                        html += '<div class="tree" mark="'+dataMark+'" name="'+dataName+'" vallist="'+vallist+'" url="'+dataUrl+'"  verify="'+dataVerify+'" nameVerify="'+dataNameVerify+'" checkbar="'+checkbar+'" width="'+width+'" height="'+height+'"  level="'+level+'">';
                            var options = {};
                            options = {
                                'type':'tree',
                                'mark':dataMark,
                                'name':dataName,
                                'url':dataUrl,
                                'verify':dataVerify,
                                'nameVerify':dataNameVerify,
                                'checkbar':checkbar,
                                'width':width,
                                'height':height,
                                'level':level,
                                'vallist':data[vallist]?data[vallist]:'',
                                'value':data[dataNameLog]?data[dataNameLog]:'',
                                'lay_tips':lay_tips
                            };
                            html += _this.addHtml(options);
                        html += '</div>';
                    html += '</div>';
                    return html;
                break;
                case 'select':
                    var dataMark =  $(obj).attr('data-mark')+'_'+opt.num;
                    var layer_hover = lay_tips?'layer_hover':'';
                    var lay_tips = lay_tips?'lay_tips='+lay_tips:'';

                    var filter =  $(obj).attr('filter');
                    filter = filter?'lay-filter='+filter:'';

                    var data_value =  $(obj).attr('data_value');
                    data_value  = eval('(' + data_value + ')');
                    
                    html += '<div class="layui-input-'+layui_input+' '+layer_hover+' '+dataClass+'" style="'+layer_style+'margin-bottom: 10px;" '+lay_tips+'>';
                        html += '<select name="'+dataName+'" class="layui-input-inline "  '+filter+' >';
                            var class_ = '';
                            $.each(data_value,function(idx,objs){
                                var data_class = objs.class?'data_class='+objs.class:'';
                                var selected = data[dataNameLog] == objs.value?'selected':'';
                                var value = objs.value?objs.value:'';
                                data_class?(data[dataNameLog]?(data[dataNameLog] == objs.value?class_ = objs.class:''):(idx == 0?class_ = objs.class:'')):'';
                                html += '<option value="'+value+'" '+data_class+' '+selected+'>'+objs.text+'</option>';
                        　　});
                        html += '</select>';
                    html += '</div>';
                    if( class_ ){
                        var divList = $(body).find('.'+class_);
                        divList.each( function(){
                            html += _this.manyJsonHtmlTwo(this,body,opt,data);
                        })
                    }
                    return html;
                break;
                case 'radio':
                    var layer_hover = lay_tips?'layer_hover':'';
                    var lay_tips = lay_tips?'lay_tips='+lay_tips:'';

                    var data_value =  $(obj).attr('data_value');
                    data_value  = eval('(' + data_value + ')');
                    html += '<div class="layui-input-'+layui_input+' '+layer_hover+' '+dataClass+'" style="'+layer_style+'margin-bottom: 10px;" '+lay_tips+'>';
                    $.each(data_value,function(idx,objs){
                        var checked = '';
                        data[dataNameLog]?( data[dataNameLog] == objs.value?checked='checked':''):(idx == 0?checked = 'checked':'');
                        html += '<input type="radio" name="'+dataName+'" value="'+objs.value+'" title="'+objs.text+'" '+checked+'>';
                    });
                    html += '</div>';
                    return html;
                break;
                case 'textarea':
                    var layer_hover = lay_tips?'layer_hover':'';
                    var lay_tips = lay_tips?'lay_tips='+lay_tips:'';
                    var dataMax =  $(obj).attr('data-max');
                    var dataValue =  data[dataNameLog]?data[dataNameLog]:'';
                    html += '<div class="layui-input-'+layui_input+' '+layer_hover+' '+dataClass+'" '+lay_tips+' style="'+layer_style+'margin-bottom: 10px;">';
                        html += '<textarea name="'+dataName+'" placeholder="请输入'+dataNameVerify+'" autocomplete="off" class="layui-textarea layext-text-tool" maxlength="'+dataMax+'" lay-verify="'+dataVerify+'" name-verify="'+dataNameVerify+'">'+dataValue+'</textarea>';
                    html += '</div>';
                    return html;
                break;
                default:
            } 
        },
        deleteMany:function()
        {
            $(document).on("click", ".deleteMany", function (e) {
                $(this).parent().parent().attr('_remove_','0');
                $(this).prev().val('0');
                $(this).parent().parent().hide();
                $(this).parent().parent().find("[lay-verify='noNull']").attr('lay-verify','');
                // $(this).parent().parent().remove();
                form.render();
            });
        },
        TMapAdd:function()
        {
            $('.TMap').each( function(){
                var name = $(this).attr('name')?$(this).attr('name'):'TMap';
                var pro = $(this).attr('pro')?$(this).attr('pro'):'pro';
                var city = $(this).attr('city')?$(this).attr('city'):'city';
                var area = $(this).attr('area')?$(this).attr('area'):'area';
                var longitude = $(this).attr('longitude')?$(this).attr('longitude'):'longitude';
                var dimension = $(this).attr('dimension')?$(this).attr('dimension'):'dimension';

                var address = $(this).attr('address')?$(this).attr('address'):'address';
                $("#"+name).click(function(){
                    // 直接打开地图
                    var long = $('.'+longitude ).val();
                    var dime = $('.'+dimension ).val();
                    var value = long?[long,dime]:undefined;
                    TMap.open({
                        key:"NZQBZ-NZY6Q-TTL5W-GYJ3X-X6HL3-FWBOB",
                        longitude:value,
                        dialog: {
                            //弹窗设置
                        },
                        onChoose: function (point, adress, myIndex,result) {
                            if( point == ''){
                                layer.msg('地址不能为空！');
                                return false;
                            }
                            $('.'+name+'_div').show();
                            $('.'+address+'_td').text(result.address_component.street_number);
                            $('.'+pro+'_td').text(result.address_component.province);
                            $('.'+city+'_td').text(result.address_component.city);
                            $('.'+area+'_td').text(result.address_component.district);
                            $('.'+longitude+'_td').text(result.location.lat);
                            $('.'+dimension+'_td').text(result.location.lng);

                            $('.'+address).val(result.address_component.street_number);
                            $('.'+pro).val(result.address_component.province);
                            $('.'+city).val(result.address_component.city);
                            $('.'+area).val(result.address_component.district);
                            $('.'+longitude).val(result.location.lat);
                            $('.'+dimension).val(result.location.lng);

                            layer.close(myIndex)
                            //point：经纬度坐标点，数据格式 (经度,纬度)；
                            //adress: 选中点中文地址；
                            //myIndex: 当前窗口层级，可使用 layer.close(layer.index)来关闭地图弹窗层；
                            // console.log("point:"+ point +"\nadress:"+ adress +"\nmyIndex:"+ myIndex);
                        }
                    });
                });
            })
        },
        //限制
        jurisdiction:function()
        {
            $('.layui-form').attr('url','');
            $('.layui-form').find("input").each(function () {
                
                var type = $(this).attr('type');
                $(this).attr('disabled', 'disabled');
                $(this).addClass('layui-disabled');
            })
            //禁止select 输入
            $('.layui-form').find("select").each(function () {
                $(this).attr('disabled', 'disabled');
                $(this).addClass('layui-disabled');
            })
            //禁止textarea 输入
            $('.layui-form').find("textarea").each(function () {
                $(this).attr('disabled', 'disabled');
                $(this).addClass('layui-disabled');
            })
            //删除树选择案例
            $('.tree').removeClass('tree');
            //删除图片选择按钮
            $(".iconPicker").each( function(){
                $(this).next().attr('id','');
            })
            //删除新增按钮
            $(".manyJsonButton").each( function(){
                $(this).parent().parent().remove();
            })
            //删除移除按钮
            $('.layui-form').find(".deleteMany").each(function () {
                $(this).parent().remove();
            })
            //删除页面所有按钮
            $('.layui-form').find("button").each(function () {
                $(this).remove();
            })
            //删除多图片删除按钮
            $('.layui-form').find(".layui-icon-delete").each(function () {
                $(this).remove();
            })
            //删除数字 ++
            $('.layui-form').find(".number-input-add").each(function () {
                $(this).remove();
            })
            //删除数字 --
            $('.layui-form').find(".number-input-subtract").each(function () {
                $(this).remove();
            })
            //删除提交按钮
            $('.layui-form').find(".layer-footer").each(function () {
                $(this).remove();
            })
        },
        numinput: function (options = {}) 
        {
            // 使用方法
            // 在input 添加 class="layui-input-number"
            // 可设置参数
            //      min 最小值
            //      max 最大值
            //      data-prec   精度配置
            //      step        步进
            //      
            // 123：123键置顶, 789：789键置顶
            var topBtns = options.topBtns == undefined?'123':options.topBtns;
            // 右侧功能按钮开关
            var rightBtns = options.rightBtns == undefined?true:options.rightBtns;
            // 功能按钮提示开关
            var showTips = options.showTips == undefined?true:options.showTips;
            // 是否监听键盘事件
            var listening = options.listening == undefined?true:options.listening;
            // 批量配置默认小数精确度，默认 -1 不处理精确度，0 表示禁止输入小数
            var defaultPrec = options.defaultPrec == undefined?0:options.defaultPrec;
            // 自定义 z-index
            var zIndex = options.zIndex == undefined?200:options.zIndex;
            numinput.init({
                topBtns: topBtns,
                rightBtns: rightBtns,
                showTips: showTips,
                listening: listening,
                defaultPrec: defaultPrec,
                // 初始化回调，无参
                initEnd: options.initEnd,
                // 触发显示回调，参数为当前输入框和数字键盘的 jQuery 对象
                showEnd: options.showEnd,
                // 隐藏键盘回调，参数为当前输入框的 jQuery 对象
                hideEnd: options.hideEnd,
                zIndex: zIndex
            });
        },
        textool:function(options = {})
        {
            // 使用方法
            // 在input 添加 class="layext-text-tool"
            // 可设置参数
            //      maxlength       输入框最大长度
            //      
            // 根据元素 id 值单独渲染，为空默认根据 class='layext-text-tool' 批量渲染
            var eleId = options.eleId == undefined?null:options.eleId;
            // 批量设置输入框最大长度，可结合 eleId 单独设置最大长度
            var maxlength = options.maxlength == undefined?-1:options.maxlength;
            // 初始化展开，默认展开，否则收起
            var initShow = options.initShow == undefined?false:options.initShow;
            // 工具条是否位于输入框内部，默认位于外部
            var inner = options.inner == undefined?true:options.inner;
            // 对齐方向，默认右对齐，可选左对齐 'left'
            var align = options.align == undefined?'right':options.align;
            // 启用指定工具模块，默认依次为字数统计、复制内容、重置内容、清空内容，按数组顺序显示
            var tools = options.tools == undefined?['count', 'copy', 'reset', 'clear']:options.tools;
            // 工具按钮提示类型，默认为 'title' 属性，可选 'laytips'，使用 layer 组件的吸附提示， 其他值不显示提示
            var tipType = options.tipType == undefined?'title':options.tipType;
            // 吸附提示背景颜色
            var tipColor = options.tipColor == undefined?'#01AAED':options.tipColor;
            // 工具条字体颜色
            var color = options.color == undefined?'#666666':options.color;
            // 工具条背景颜色
            var bgColor = options.bgColor == undefined?'#FFFFFF':options.bgColor;
            // 工具条边框颜色
            var borderColor = options.borderColor == undefined?'#E6E6E6':options.borderColor;
            // 工具条附加样式类名
            var className = options.className == undefined?'':options.className;
            // z-index
            var zIndex = options.zIndex == undefined?200:options.zIndex;
            textool.init({
                eleId: eleId,
                maxlength: maxlength,
                // 初始化回调，无参
                initEnd: options.initEnd,
                // 显示回调，参数为当前输入框和工具条面板的 jQuery 对象
                showEnd: options.showEnd,
                // 隐藏回调，参数为当前输入框和工具条面板的 jQuery 对象
                hideEnd: options.hideEnd,
                initShow: initShow,
                inner: inner,
                align: align,
                tools: tools,
                tipType: tipType,
                tipColor: tipColor,
                color: color,
                bgColor: bgColor,
                borderColor: borderColor,
                className: className,
                zIndex: zIndex
            });
        },
        numberInput:function(options = {})
        {
            $('.numberInput').each(function () {
                var update = $(this).attr('update');
                if( update != 1 ){
                    $(this).attr('defaultValue',$(this).val());
                    // 最大值
                    var max = $(this).attr('max');
                    max = max == undefined?100:max;
                    // 最小值
                    var min = $(this).attr('min');
                    min = min == undefined?1:min;
                    // 精度
                    var precision = $(this).attr('precision');
                    precision = precision == undefined?0:precision;
                    // 步数
                    var step = $(this).attr('step');
                    step = step == undefined?10:step;
                    // 宽度
                    var numWidth = $(this).attr('numWidth');
                    numWidth = numWidth == undefined?100:numWidth;
                    // 默认值
                    var defaultValue = $(this).attr('defaultValue');
                    defaultValue = defaultValue == undefined?0:defaultValue;
                    // 是否允许空?
                    var allowEmpty = $(this).attr('allowEmpty');
                    allowEmpty = allowEmpty == undefined?false:allowEmpty;
                    // 是否自动全选
                    var autoSelect = $(this).attr('autoSelect');
                    autoSelect = autoSelect == undefined?false:autoSelect;
                    numberInput.init($(this), {
                        max: max,
                        min: min,
                        precision: precision,
                        step: step,
                        numWidth: numWidth,
                        defaultValue: defaultValue,
                        allowEmpty: allowEmpty,
                        autoSelect: autoSelect,
                    });
                }
            })
        },
        iconPickerFa:function()
        {
            $(".iconPicker").each( function(){
                var update = $(this).attr('update');
                if( update != 1 ){
                    $(this).attr('update',1);
                    iconPickerFa.render({elem: '.'+ $(this).attr('lay-filter')});
                }
            })
        },
        treeAdd:function()
        {
            // 使用方法
            // 在input 添加 class="tree"
            // 可设置参数
            //      name                字段名称
            //      url             选择的URL
            //      width           宽度
            //      height          高度
            //      checkbar        开启多选 1为开启 2为关闭
            //      level           限制
            $('.tree').each( function(){
                var update = $(this).attr('update');
                if( update != 1 ){
                    $(this).attr('update',1);
                    var _this_ = $(this);
                    var mark = _this_.attr('mark');
                    var url = _this_.attr('url');

                    //宽度
                    var width = _this_.attr('width');
                    width = width?width:'400px';
                    //高度
                    var height = _this_.attr('height');
                    height = height?height:"90%";
                    // 开启多选
                    var checkbar = _this_.attr('checkbar');
                    checkbar = checkbar == 1?true:false;
                    //限制
                    var level = _this_.attr('level');
                    level = level?level:0;

                    $(".Tree"+mark+"Button").click(function(){
                        
                        var DTree = null;
                        layer.open({
                            type: 1, //type:0 也行
                            title: '选择树',
                            area: [width, height],
                            content: '<ul id="openTree'+mark+'" class="dtree" data-id="0"></ul>',
                            btn: ['确认选择','清空','取消'],
                            success: function(layero, index){
                                var data_s = '';
                                $("div[url='"+url+"']  input[type=hidden]").each(function(){
                                    var _remove_ = $(this).parent().parent().parent().attr('_remove_');
                                    _remove_ == "1"?( $(this).val()?( data_s?data_s += ','+$(this).val():data_s = $(this).val() ):'' ):'';
                                });
                                if( data_s ){
                                    url = url+'?data='+data_s
                                }
                                DTree = dtree.render({
                                    obj: $(layero).find("#openTree"+mark)
                                    ,url: url
                                    ,height:'full'
                                    ,width:"90%"
                                    ,dataStyle: "layuiStyle"  //使用layui风格的数据格式
                                    ,response:{message:"msg",statusCode:0}  //修改response中返回数据的定义
                                    ,line: true  // 显示树线
                                    ,accordion: true  // 开启手风琴
                                    ,checkbar: checkbar         //开启多选
                                    ,checkbarType: 'p-casc' //多选配置
                                    ,skin: "laySimple"      //设置风格
                                    ,toolbar:true // 开启工具栏
                                    ,toolbarShow:["searchNode","refresh","moveDown","moveUp"] // 工具栏自带的按钮制空
                                    ,menubar:true
                                    ,menubarTips:{
                                        toolbar:["searchNode","refresh","moveDown","moveUp"],  // 指定工具栏吸附的menubar按钮
                                        group:[] // 按钮组制空
                                    }
                                    ,iconfont:["dtreefont", "layui-icon", "iconfont"]
                                    ,done: function(data, obj){  //使用异步加载回调
                                        var reportId = $(".Tree"+mark+"Id").val();
                                        if( checkbar ){
                                            dtree.chooseDataInit("openTree"+mark,reportId); // 多选初始化选中
                                        }else{
                                            dtree.dataInit("openTree"+mark, reportId);   // 单选初始化值
                                        }
                                    }
                                });
                                //点击节点
                                dtree.on("node('openTree'"+mark+")" ,function(obj){
                                    if(!obj.param.leaf){
                                        DTree.clickSpread(obj.dom);  //调用内置函数展开节点
                                    }
                                });
                            },
                            yes: function(index, layero) {
                                if( checkbar ){
                                    //多选处理
                                    var flag = false;
                                    var params = dtree.getCheckbarNodesParam("openTree"+mark); // 获取选中值
                                    if(params.length == 0){
                                        layer.msg("请至少选择一个节点");
                                    }else{
                                        var arrayRes = [];
                                        var html = '';
                                        var nodeId = '';
                                        $.each( params, function(i,item){
                                            if( level != 0 ){
                                                if( item['level'] > level){
                                                    arrayRes.push(item);
                                                    html += '<span class="layui-badge layui-bg-blue" style="top:10px;margin-right: 5px;">'+item.context+'</span>';
                                                    nodeId = nodeId == ''?item.nodeId:nodeId+','+item.nodeId;
                                                }
                                            }else{
                                                arrayRes.push(item);
                                                html += '<span class="layui-badge layui-bg-blue" style="top:10px;margin-right: 5px;">'+item.context+'</span>';
                                                nodeId = nodeId == ''?item.nodeId:nodeId+','+item.nodeId;
                                            }
                                        })

                                        if( arrayRes.length == 0 ){
                                            layer.msg("请至少选择一个节点");
                                        }else{
                                            $(".Tree"+mark+"Button").empty();
                                            $(".Tree"+mark+"Button").append(html);
                                            $(".Tree"+mark+"Id").val(nodeId);
                                            flag = true;
                                        }
                                    }
                                    if(flag){ layer.close(index); }

                                }else{
                                    //单选处理
                                    var flag = true;
                                    var param = dtree.getNowParam("openTree"+mark);
                                    if($.isEmptyObject(param)){
                                        layer.msg("请至少选择一个节点");
                                        flag = true;
                                    }else{
                                        if( param["level"] <= level){
                                            layer.msg("该节点禁止选择！");
                                            flag = false;
                                        }else{
                                            $(".Tree"+mark+"Button").empty();
                                            var html = '<span class="layui-badge layui-bg-blue" style="top:10px;margin-right: 5px;">'+param["context"]+'</span>';
                                            $(".Tree"+mark+"Button").append(html);
                                            $(".Tree"+mark+"Id").val(param["nodeId"]);
                                        }
                                    }
                                }
                                if(flag){layer.close(index);}
                            },
                            btn2: function(index, layero){
                                $(".Tree"+mark+"Button").empty();
                                var html = '<span style="position: relative;top:10px;color: #999">点击添加</span>';
                                $(".Tree"+mark+"Button").append(html);
                                $(".Tree"+mark+"Id").val('');
                            }
                        });
                    });
                }
            })
        },
        singleImage:function()
        {
            $('.singleImage').each( function(){
                var update = $(this).attr('update');
                if( update != 1 ){
                    $(this).attr('update',1);
                    var mark = $(this).attr('mark');
                    upload.render({
                        elem: '#'+'singleImage_'+mark         //绑定元素
                        ,auto:false                                 //是否选完文件后自动上传。
                        ,multiple:false                             //是否允许多文件上传。
                        ,acceptMime:'image/*'
                        ,choose:function(obj){
                            // 选择文件后的回调函数。
                            obj.preview(function(index, file, result){
                                $('.singleImage_'+mark+'_img').attr('src',result);
                                $('.singleImage_'+mark+'Id').val(result);
                            })      
                        }
                    });
                }
            });
        },
        singleVideo:function()
        {
            $('.singleVideo').each( function(){
                var update = $(this).attr('update');
                if( update != 1 ){
                    $(this).attr('update',1);
                    var mark = $(this).attr('mark');
                    var url = $(this).attr('url');
                    upload.render({
                        elem: '#'+'singleVideo_'+mark   
                        ,url:url   
                        ,bindAction:'.singleVideo_'+mark+'_span'
                        ,data: {
                            id: function(){
                                return $('.singleVideo_'+mark+'_span').attr('data-id');
                            }
                        }  
                        ,auto:false                                 
                        ,multiple:false 
                        ,accept:'video'                            
                        ,before: function(obj){ 
                            var html = '';
                            $('.singleVideo').each( function(){
                                var markVideo = $(this).attr('mark');
                                var val = $('.singleVideo_'+markVideo+'Id').val();
                                if( val == 1 ){
                                    html += '<div class="layui-progress layui-progress-big" lay-filter="progress-'+markVideo+'"  lay-showPercent="yes"><div class="layui-progress-bar"></div></div>';
                                    html += '</br>';
                                }
                            })
                            var progressLayer = layer.open({
                                type:0,
                                title:false,
                                closeBtn:0,
                                btn:false,
                                content:html
                            });
                        }
                        ,progress: function (n, elem, res, index) { 
                            var percent = n + '%'; 
                            element.progress('progress-'+mark, percent);
                            element.render();
                        }
                        ,choose:function(obj){
                            obj.preview(function(index, file, result){
                                $('.singleVideo_'+mark+'_video').attr('src',result);
                                $('.singleVideo_'+mark+'Id').val("1");
                            })      
                        }
                        ,done:function(res, index, upload){
                            element.progress('progress-'+mark, '100%');
                            element.render();
                            layer.close(layer.index); 
                            $('.singleVideo_'+mark+'Id').val("100");

                            var res = 1;
                            $('.singleVideo').each( function(){
                                var markVideo = $(this).attr('mark');
                                var markVideoVal = $('.singleVideo_'+markVideo+'Id').val();
                                if( markVideoVal != 2 ){
                                    if( markVideoVal != "100" ){
                                        res = 2;
                                    }
                                }
                            })
                            if( res == 1 ){
                                var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                                parent.layer.close(index); //再执行关闭
                            }
                        }
                    });
                }
            });
        },
        manyPic:function()
        {
            var _this = this;
            $('.manyPic').each( function(key,value){
                if( key == 0 ){
                    var style = '<style>';
                        style += '.layui-upload-img { width: 90px; height: 90px; margin: 0; }';
                        style += '.pic-more { width:100%; left; margin: 10px 0px 0px 0px;}';
                        style += '.pic-more li { width:90px; float: left; margin-right: 5px;}';
                        style += '.pic-more li .layui-input { display: initial; }';
                        style += '.pic-more li a { position: absolute; top: 0; display: block; }';
                        style += '.pic-more li a i { font-size: 24px; background-color: #008800; }   ';
                        style += '.slide-pc-priview .item_img img{ max-width: 90px; max-height: 90px;}';

                        style += '.slide-pc-priview li{position: relative;}';
                        style += '.slide-pc-priview li .operate{ color: #000; display: none;}';
                        style += '.slide-pc-priview li .toleft{ position: absolute;top: 40px; left: 1px; cursor:pointer;}';
                        style += '.slide-pc-priview li .toright{ position: absolute;top: 40px; right: 1px;cursor:pointer;}';
                        style += '.slide-pc-priview li .close{position: absolute;top: 5px; right: 5px;cursor:pointer;}';
                        style += '.slide-pc-priview li .search{position: absolute;top: 5px; right: 5px;cursor:pointer;}';
                        style += '.slide-pc-priview li:hover .operate{ display: block;}  ';
                        style += '.slide-pc-priview li:hover .cover{ display: block;}  ';

                        style += '.cover{ ';
                            style += 'position:absolute;left:0px;top:0px;';
                            style += 'background:rgba(0, 0, 0, 0.8);';
                            style += 'width:100%;  ';
                            style += 'height:100%;';
                            style += 'filter:alpha(opacity=60);  ';
                            style += 'opacity:0.6; ';
                            style += 'display:none; ';
                            style += 'z-Index:999;  ';
                        style += '}';
                        style += '.operate i{ z-Index:9999;color:#fff;}'
                    style += '</style>';
                    $('body').append(style);
                    //点击多图上传的X,删除当前的图片    
                    $("body").on("click",".close",function(){
                        var that = $(this);
                        var id = that.closest("li").children().children("input:first").val();
                        var ival = parseInt(id);
                        if( !isNaN(ival)){
                            $.ajax({
                                url: '/flow/image/del_image',
                                type: "post",
                                data:{id:ival},
                                dataType: "json",
                                success:function(res){
                                    that.closest("li").remove();
                                }
                            })
                        }else{
                            that.closest("li").remove();
                        }
                    });
                    $("body").on("click",".search",function(){
                        var that = $(this);
                        var data = [];
                        var start = 0;
                        that.closest("li").children().children("img:first").addClass('clickImg');
                        $.each($('#admin_imgs-ul').find('img'),function(index,img){
                            var oldSrc = $(this).attr('src');
                            start = $(this).hasClass('clickImg')?index:0;
                            var imgs = {
                                "alt": "图片",
                                "pid": index, //图片id
                                "src": oldSrc, //原图地址
                                "thumb": oldSrc //缩略图地址
                            }
                            data.push(imgs);
                        })
                        var img_json = {
                            "title": "", //相册标题
                            "id": 123, //相册id
                            "start": start, //初始显示的图片序号，默认0
                            "data": data
                        } 
                        layer.photos({
                            photos: img_json
                            ,anim: 0
                            ,end:function()
                            {$(".clickImg").removeClass("clickImg");} 
                        });
                    });
                    //多图上传点击<>左右移动图片
                    $("body").on("click",".pic-more ul li .toleft",function(){
                        var li_index=$(this).closest("li").index();
                        if(li_index>=1){
                            $(this).closest("li").insertBefore($(this).closest("ul").find("li").eq(Number(li_index)-1));
                        }
                    });
                    $("body").on("click",".pic-more ul li .toright",function(){
                        var li_index=$(this).closest("li").index();
                        $(this).closest("li").insertAfter($(this).closest("ul").find("li").eq(Number(li_index)+1));
                    });
                    _this.scrollZoomIn();
                }
                var name = $(this).attr('name');
                var number = $(this).attr('number');
                number = number?number:0;
                
                var uploadRender = upload.render({
                    elem: '#'+name,
                    size: 1000,
                    exts: 'jpg|png|jpeg',
                    multiple: true,
                    auto:false,
                    choose: function(obj){
                        //将每次选择的文件追加到文件队列
                        var files = obj.pushFile();
                        //预读本地文件，如果是多文件，则会遍历。(不支持ie8/9)
                        obj.preview(function(index, file, result){
                            if( number != 0 ){
                                var length = $('#'+name+'-ul li').length;
                                if( (number - length) <= 0){
                                    layer.msg("您最多只能上传"+number+'张图片！');
                                    return false;
                                }else{
                                    uploadRender.config.elem.next()[0].value = '';
                                }
                            }
                            var html = '';
                            html += '<li class="item_img" >';
                                html += '<div class="operate" >';
                                    html += '<i class="toleft layui-icon layui-icon-shrink-right" ></i>';
                                    html += '<i class="toright layui-icon layui-icon-spread-left"></i>';
                                    html += '<i  class="close layui-icon layui-icon-delete"></i>';
                                    html += '<i style="margin-right:20px" class="search layui-icon layui-icon-search"></i>';
                                html += '</div>';
                                html += '<div style="text-align:center;line-height: 90px;">';
                                    html += '<img style="border-radius:20px" src="' + result + '" class="img" >';
                                    html += '<input type="hidden" name="'+name+'[]" value="' + result + '" />';
                                html += '</div>';

                                html += '<div class="cover"></div>';
                            html += '</li>';
                            $('#'+name+'-ul').append(html);
                            uploadRender.config.elem.next()[0].value = '';
                        });
                    }
                });
            });
        },
        formVerify:function()
        {
            form.verify({
                //判断是否为空
                noNull:function(value ,item ){
                    if( !value ){
                        var name = $(item).attr('name-verify');
                        name = name == undefined?'值':name;
                        return name+'不为空！';
                    }
                }
            });
        },
        addForm:function(body,edit = '')
        {   
            var _this = this;
            
            body.find(".TMap").each( function(key,value){
                var name = $(this).attr('name')?$(this).attr('name'):'TMap';
                var pro = $(this).attr('pro')?$(this).attr('pro'):'pro';
                var city = $(this).attr('city')?$(this).attr('city'):'city';
                var area = $(this).attr('area')?$(this).attr('area'):'area';
                var longitude = $(this).attr('longitude')?$(this).attr('longitude'):'longitude';
                var dimension = $(this).attr('dimension')?$(this).attr('dimension'):'dimension';

                var is_show = $(this).attr('is_show')?$(this).attr('is_show'):'longitude';

                var address = $(this).attr('address');
                var verify = $(this).attr('verify');
                var name_verify = $(this).attr('name-verify');
                verify = verify?verify:'';
                name_verify = name_verify?name_verify:'';

                var html = '';
                html += '<div class="">';
                    html += '<button type="button" style="margin-bottom:10px" class="layui-btn layui-btn-primary" id="'+name+'">选择地址</button>';

                    var value = edit[is_show];
                    var display = value?'':'display:none';
                    html += '<div class="'+name+'_div" style="'+display+'">';
                        html += '<table class="layui-table">';
                            html += '<tbody>';
                                html += '<tr>';
                                    html += '<th style="background:#FAFAFA;width: 50px;text-align: center;">省</th>';
                                    var pro_val =  edit[pro]?edit[pro]:'';
                                    html += '<td class="'+pro+'_td">'+pro_val+'</td>';
                                    html += '<input type="hidden" class="layui-input '+pro+'"  name="'+pro+'" lay-verify="'+verify+'" name-verify="'+name_verify+'-省">';
                                html += '</tr>';
                                html += '<tr>';
                                    html += '<th style="background:#FAFAFA;width: 50px;text-align: center;">市</th>';
                                    var city_val =  edit[city]?edit[city]:'';
                                    html += '<td class="'+city+'_td">'+city_val+'</td>';
                                    html += '<input type="hidden" class="layui-input '+city+'"  name="'+city+'" lay-verify="'+verify+'" name-verify="'+name_verify+'-市">';
                                html += '</tr>';
                                html += '<tr>';
                                    html += '<th style="background:#FAFAFA;width: 50px;text-align: center;">区</th>';
                                    var area_val =  edit[area]?edit[area]:'';
                                    html += '<td class="'+area+'_td">'+area_val+'</td>';
                                    html += '<input type="hidden" class="layui-input '+area+'"  name="'+area+'" lay-verify="'+verify+'" name-verify="'+name_verify+'-区">';
                                html += '</tr>';
                                html += '<tr>';
                                    html += '<th style="background:#FAFAFA;width: 50px;text-align: center;">经度</th>';
                                    var longitude_val =  edit[longitude]?edit[longitude]:'';
                                    html += '<td class="'+longitude+'_td">'+longitude_val+'</td>';
                                    html += '<input type="hidden" class="layui-input '+longitude+'"  name="'+longitude+'" lay-verify="'+verify+'" name-verify="'+name_verify+'-经度">';
                                html += '</tr>';
                                html += '<tr>';
                                    html += '<th style="background:#FAFAFA;width: 50px;text-align: center;">维度</th>';
                                    var dimension_val =  edit[dimension]?edit[dimension]:'';
                                    html += '<td class="'+dimension+'_td">'+dimension_val+'</td>';
                                    html += '<input type="hidden" class="layui-input '+dimension+'"  name="'+dimension+'" lay-verify="'+verify+'" name-verify="'+name_verify+'-维度">';
                                html += '</tr>';
                            html += '</tbody>';
                        html += '</table>';
                    html += '</div>';

                html += '</div>';
                $(this).append(html);
            })
            //input  赋值
            $(body).find("input").each(function () {
                var type = $(this).attr('type');
                var name = $(this).attr('name');
                if( edit[name] ){
                    var value = edit[name];
                    switch(type) {
                         case 'text':
                            $(this).val(value);
                        break;
                        case 'hidden':
                            $(this).val(value);
                        break;
                         case 'radio':
                            if( $(this).val() == value){
                                $(this).prop("checked","checked");
                            }else{
                                $(this).removeAttr('checked');
                            }
                        break;
                    } 
                }
            })
            //select 赋值
            $(body).find("select").each(function () {
                var name = $(this).attr('name');
                if( edit[name]  ){
                    $(this).val(edit[name]);
                }
            })
            //textarea  赋值
            $(body).find("textarea").each(function () {
                var name = $(this).attr('name');
                if( edit[name] ){
                    $(this).val(edit[name]);
                }
            })
            //树状
            body.find(".tree").each( function(){
                var html = '';
                var lay_tips = $(this).attr('lay_tips');
                var options = {};
                options = {
                    'type':'tree',
                    'mark':$(this).attr('mark'),
                    'name':$(this).attr('name'),
                    'url':$(this).attr('url'),
                    'verify':$(this).attr('verify')?$(this).attr('verify'):'',
                    'nameVerify':$(this).attr('nameVerify')?$(this).attr('nameVerify'):'',
                    'checkbar':$(this).attr('checkbar'),
                    'width':$(this).attr('width')?$(this).attr('width'):'400px',
                    'height':$(this).attr('height')?$(this).attr('height'):'90%',
                    'level':$(this).attr('level')?$(this).attr('level'):'0',
                    'vallist':edit[$(this).attr('vallist')],
                    'value':edit[$(this).attr('name')]?edit[$(this).attr('name')]:'',
                    'lay_tips':lay_tips
                };
                html += _this.addHtml(options);

                
                $(this).append(html);
            })
            //多图片
            body.find(".manyPic").each( function(key,value){

                var name = $(this).attr('name');
                var number = $(this).attr('number');

                var vallist = $(this).attr('vallist');
                vallist = vallist?vallist:name;

                var html = '';
                html += '<div class="layui-upload">';
                    html += '<button type="button" class="layui-btn layui-btn-primary pull-left" id="'+name+'">选择图片</button>';
                    if(number != 0 ){
                        html += '<div class="layui-form-mid layui-word-aux" style="margin-left:10px">* 可上传'+number+'张图片</div>';
                    }
                    html += '<div style="clear:both;"></div>';
                    html += '<div class="pic-more">';
                        html += '<ul class="pic-more-upload-list slide-pc-priview" id="'+name+'-ul">';
                        html += '</ul>';
                    html += '</div>';
                html += '</div>';
                $(this).append(html);
                var image_array = edit[vallist];
                if( image_array){
                    var html = '';
                    for( var i = 0; i < image_array.length; i++ ){
                        html += '<li class="item_img">';
                            html += '<div class="operate">';
                                html += '<i class="toleft layui-icon layui-icon-shrink-right"></i>';
                                html += '<i class="toright layui-icon layui-icon-spread-left"></i>';
                                html += '<i  class="close layui-icon layui-icon-delete"></i>';
                                html += '<i style="margin-right:20px" class="search layui-icon layui-icon-search"></i>';
                            html += '</div>';
                            html += '<div style="text-align:center;line-height: 90px;">';
                                html += '<img style="border-radius:20px" src="' + image_array[i]['path'] + '" class="img" >';
                                html += '<input type="hidden" name="'+name+'[]" value="' + image_array[i]['id'] + '" />';
                            html += '</div>';
                            html += '<div class="cover"></div>';
                        html += '</li>';
                    }
                    body.find('#'+name+'-ul').append(html);
                }
            })
            //单图片
            body.find(".singleImage").each( function(){
                var html = '';
                var options = {};
                var name = $(this).attr('name');
                var lay_tips = $(this).attr('lay_tips');
                options = {
                    'type':'singleImage',
                    'mark':$(this).attr('mark'),
                    'name':name,
                    'width':$(this).attr('width')?$(this).attr('width'):'100px',
                    'verify':$(this).attr('verify')?$(this).attr('verify'):'',
                    'nameVerify':$(this).attr('nameVerify')?$(this).attr('nameVerify'):'',
                    'remarks':$(this).attr('remarks')?$(this).attr('remarks'):'20px',
                    'imageUrl':!edit[name]?'/images/logo.png':'/upload/'+edit[name],
                    'value':!edit[name]?'':'1',
                    'lay_tips':lay_tips
                };
                html += _this.addHtml(options);
                $(this).append(html);
            })

            body.find(".singleVideo").each( function(){
                var html = '';
                var options = {};
                var name = $(this).attr('name');
                var lay_tips = $(this).attr('lay_tips');
                options = {
                    'type':'singleVideo',
                    'mark':$(this).attr('mark'),
                    'name':name,
                    'width':$(this).attr('width')?$(this).attr('width'):'100px',
                    'verify':$(this).attr('verify')?$(this).attr('verify'):'',
                    'nameVerify':$(this).attr('nameVerify')?$(this).attr('nameVerify'):'',
                    'remarks':$(this).attr('remarks')?$(this).attr('remarks'):'20px',
                    'videoUrl':!edit[name]?'/images/video.mp4':'/upload/'+edit[name],
                    'value':!edit[name]?'':'2',
                    'lay_tips':lay_tips
                };
                html += _this.addHtml(options);
                $(this).append(html);
            })
            body.find(".manyJson").each( function(){
                var name = $(this).attr('name');
                var manyArray = edit[name];
                if( manyArray ){
                    for( var i = 0; i < manyArray.length; i++ ){
                        _this.manyJsonHtml($(this),body,manyArray[i]);
                    }
                }
            })
            if( edit['id'] ){
                $(body).find(".Id").val(edit['id']);
            }
        },
        addHtml:function(options = {})
        {
            var html = '';
            var layer_hover = options.lay_tips?'layer_hover':'';
            var lay_tips = options.lay_tips?'lay_tips='+options.lay_tips:'';
            if( options.type == 'singleImage'){
                html += '<div id="singleImage_'+options.mark+'" class="'+layer_hover+'" '+lay_tips+' style="margin: 10px;width: '+options.width+' ;overflow: hidden;border-radius:'+options.remarks+';border: 1px solid #ccc;cursor:pointer;">';
                    html += '<img class="singleImage_'+options.mark+'_img" style="width: '+options.width+';" src="'+options.imageUrl+'" alt="">';
                html += '</div>';
                html += '<input type="hidden" lay-verify="'+options.verify+'" name-verify="'+options.nameVerify+'" name="'+options.name+'" class="layui-input singleImage_'+options.mark+'Id" value="'+options.value+'">';
            }else if( options.type == 'singleVideo' ){
                html += '<div id="singleVideo_'+options.mark+'" class="'+layer_hover+'" '+lay_tips+' style="margin: 10px;width: '+options.width+' ;overflow: hidden;border-radius:'+options.remarks+';border: 1px solid #ccc;cursor:pointer;">';
                    html += '<video class="singleVideo_'+options.mark+'_video" style="width: '+options.width+';" src="'+options.videoUrl+'" controls="controls">';
                    html += '您的浏览器不支持 video 标签。';
                    html += '</video>';
                html += '</div>';
                html += '<span class="singleVideo_'+options.mark+'_span" ></span>'
                html += '<input type="hidden" lay-verify="'+options.verify+'" name-verify="'+options.nameVerify+'" name="'+options.name+'[value]" class="layui-input singleVideo_'+options.mark+'Id" value="'+options.value+'">';
                html += '<input type="hidden"  name="'+options.name+'[mark]" class="layui-input" value="'+options.mark+'">';
            }else if( options.type == 'tree' ){
                html += '<div  class="layui-input Tree'+options.mark+'Button '+layer_hover+'" '+lay_tips+'  style="cursor:pointer;overflow:hidden;height:auto;min-height:38px;padding-bottom:20px;">';
                if( !options.vallist ){
                    html += '<span style="position: relative;top:10px;color: #999">点击添加</span>';
                }else{
                    if( typeof options.vallist == 'string'){
                        html += '<span class="layui-badge layui-bg-blue" style="top:10px;margin-right: 5px;">'+options.vallist+'</span>';
                    }else{
                        for( var i = 0; i < options.vallist.length; i++ ){
                            html += '<span class="layui-badge layui-bg-blue" style="top:10px;margin-right: 5px;">'+options.vallist[i]+'</span>';
                        }
                    }
                }
                html += '</div>';
                html += '<input type="hidden" lay-verify="'+options.verify+'" name-verify="'+options.nameVerify+'" name="'+options.name+'" class="layui-input Tree'+options.mark+'Id"  placeholder="" value="'+options.value+'">';
            }
            return html;
        },
        layuiFilter:function()
        {
            var _this = this;
            form.on('select(layuiFilter)', function(data){
                var one = $(data.elem).parent().parent();

                $(data.elem).find("option").each(function(){
                    var data_class = $(this).attr('data_class');
                    one.find("."+data_class+'_div').remove();
                })
                var c = $(data.elem).find("option:selected").attr("data_class");
                var divList = one.parent().find('.manyJson').find('.'+c);
                var body = $('body');
                var html = '';
                var num = one.attr('num');
                var name = one.parent().find('.manyJson').attr('name')+'['+num+']';
                var dataClass = one.parent().find('.manyJson').attr('data-class');
                var remove = one.parent().find('.manyJson').attr('remove');
                remove = remove == undefined?1:remove;
                var options = {
                    num:num,
                    name:name,
                    dataClass:dataClass,
                    remove:remove
                }
                divList.each( function(){
                    html += _this.manyJsonHtmlTwo(this,body,options);
                })
                $(data.elem).parent().after(html);
                _this.numberInput();        //index 转数组 加加减减
                form.render();
            });      
        },
        layThis:function()
        {
            var tip_index = 0;
            $(document).on('mouseenter', '.layer_hover', function(){
                var content = $(this).attr('lay_tips');
                tip_index = layer.tips(content, $(this), { time: 0});
            }).on('mouseleave', '.layer_hover', function(){
                layer.close(tip_index);
            });
        },
        delPower:function(body,url)
        {
            $.post(
                "/admin/prohibit",
                {
                    url:url
                },function(res){
                    if( res.code == 1 ){
                        $(body).remove();
                    }
                    return false;
                },
            'json');
        },
        //图片放大
        scrollZoomIn()
        {
            $(document).on("mousewheel DOMMouseScroll", ".layui-layer-phimg img", function (e) {
                var delta = (e.originalEvent.wheelDelta && (e.originalEvent.wheelDelta > 0 ? 1 : -1)) || // chrome & ie
                        (e.originalEvent.detail && (e.originalEvent.detail > 0 ? -1 : 1)); // firefox
                var imagep = $(".layui-layer-phimg").parent().parent();
                var image = $(".layui-layer-phimg").parent();
                var h = image.height();
                var w = image.width();
                if (delta > 0) {

                    h = h * 1.05;
                    w = w * 1.05;

                } else if (delta < 0) {
                    if (h > 100) {
                        h = h * 0.95;
                        w = w * 0.95;
                    }
                }
                imagep.css("top", (window.innerHeight - h) / 2);
                imagep.css("left", (window.innerWidth - w) / 2);
                image.height(h);
                image.width(w);
                imagep.height(h);
                imagep.width(w);
            });
        },
        //更改状态
        //title     表名
        //zname     字段名
        //status    状态值
        //idname    唯一的标识名
        //id        唯一标识的值
        ChangeState:function(title,zname,status,idname,id,callback)
        {
           
            var data = [];
            data['id'] = id;
            data['status'] = status;
            data['title'] = title;
            data['zname'] = zname;
            $.post(
                "modular-status",
                {
                    id:id,
                    idname:idname,
                    status:status,
                    title:title,
                    zname:zname
                },
                callback,
            'json');
        },
        is_404:function(url,urltwo){
            var URLSS = window.location.protocol + '//' + window.location.host;
            if( top.location.href != URLSS+url){
                window.location.href=URLSS+urltwo; 
            }
        }
    };
    //输出接口
    exports('unit_call', obj);
});
