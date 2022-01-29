/**
 * date:2019/08/16
 * author:Mr.Chung
 * description:此处放layui自定义扩展
 * version:2.0.4
 */

//判断URL
// is_404();
// function is_404(){
//     var URLSS = window.location.protocol + '//' + window.location.host;
//     if( top.location.href != URLSS+'/flow'){
//         if( top.location.href != URLSS+'/flow/login/login'){
//             window.location.href=URLSS+'/404'; 
//         }
//     }
// }

window.rootPath = (function (src) {
    var URLSS = window.location.protocol + '//' + window.location.host;
    return URLSS+'/static/public/';
    
    src = document.scripts[document.scripts.length - 1].src;
    return src.substring(0, src.lastIndexOf("/") + 1);
})();
layui.config({
    base: rootPath + "lay-module/",
    version: true
}).extend({
    miniAdmin: "layuimini/miniAdmin",               // layuimini后台扩展
    miniMenu: "layuimini/miniMenu",                 // layuimini菜单扩展
    miniTab: "layuimini/miniTab",                   // layuimini tab扩展
    miniTheme: "layuimini/miniTheme",               // layuimini 主题扩展
    step: 'step-lay/step',                          // 分步表单扩展
    treetable: 'treetable-lay/treetable',           //table树形扩展
    tableSelect: 'tableSelect/tableSelect',         // table选择扩展
    iconPickerFa: 'iconPicker/iconPickerFa',        // fa图标选择扩展
    echarts: 'echarts/echarts',                     // echarts图表扩展
    echartsTheme: 'echarts/echartsTheme',           // echarts图表主题扩展
    wangEditor: 'wangEditor/wangEditor',            // wangEditor富文本扩展
    layarea: 'layarea/layarea',                     // 省市县区三级联动下拉选择器
    numinput: 'numinput/numinput',                  //  input转数字插件
    textool: 'textool/textool',                     //  监控输入框
    numberInput: 'numberInput/numberInput',         //  input转数组 加加减减
    dtree: 'dtree/dtree', 
    unit_call:'unit_call',
    TMap:'TMap/TMap',                               //地图
});
