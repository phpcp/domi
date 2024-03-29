<link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
<link rel="stylesheet" href="/static/public/font-awesome-4.7.0/css/font-awesome.min.css" media="all">
<link rel="stylesheet" href="/static/public/public.css" media="all">
<script src="/static/layui/layui.js" charset="utf-8"></script>
<script src="/static/public/lay-config.js?v=1.0.4" charset="utf-8"></script>
<style>
    .layui-table-cell {
        height: auto !important;
        white-space: pre-line;
    }
</style>
<div class="layui-row ">
    <div class="layui-col-md12 ">
        <div class="layui-panel ELEMENT" style="height: calc(100vh - 10px)">
            <div style="padding:20px;">
                <blockquote class="layui-elem-quote layui-quote-nm">
                    <div class="layui-inline"></div>
                    <div class="layui-inline">
                        <button type="button" class="layui-btn layui-btn-xs layui-bg-blue Translate">
                            一键翻译
                        </button>
                    </div>
                    <div class="layui-inline">
                        <button type="button" class="layui-btn layui-btn-xs layui-bg-red Refresh">
                            刷新
                        </button>
                    </div>
                    <div class="layui-inline">
                        <span style="margin-left:5px;" class="layui-badge-dot"></span>
                        <span style="margin-left:5px;color:red;" class="key"></span>

                        <span style="margin-left:5px;" class="layui-badge-dot layui-bg-blue"></span>
                        <span style="margin-left:5px;color:#1E9FFF;" class="text"></span>
                    </div>
                </blockquote>
                <table id="List" lay-filter="List" c_id="{{$c_id}}" k_id="{{$k_id}}"></table>
            </div>
        </div>   
    </div>
</div>  
<script src="/static/admin/wfrontPage/translate_list.js"></script>
