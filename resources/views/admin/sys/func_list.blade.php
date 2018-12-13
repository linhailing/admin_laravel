<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/13
 * Time: 12:05
 */
?>
@extends('admin/master')
@section('style')
@stop
@section('content')
    <div class="cl pd-5 bg-1 bk-gray">
        <span class="l">
            <a href="javascript:;" onclick="admin_add('添加应用','{{url("admin/sys/app_op")}}','800','500')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加应用</a>
        </span>
        <span class="r">共有数据：<strong>54</strong> 条</span>
    </div>
    <table class="table table-border table-bordered table-bg">
        <thead>
            <tr>
                <th scope="col" colspan="9">功能列表</th>
            </tr>
        </thead>
        @foreach($funcs as $app)
            <tr>
                <th scope="col" colspan="9">
                    {{$app['app_name']}}(<?=$app['app_ename']?> - <?=$app['app_status'] == 1 ? "可用":"不可用"?>)
                </th>
            </tr>
            <tr style="height:20px">
                <th class="center">编号</th>
                <th class="center">功能代码</th>
                <th class="center">功能名称</th>
                <th class="center">功能网址</th>
                <th class="center">图標16x16</th>
                <th class="center">排序</th>
                <th class="center">状态</th>
                <th class="center">操作 <a href="{{url('admin/sys/func_op?app_id='.$app['app_id'])}}"> 添加功能</a></th>
            </tr>
            @foreach($app['children'] as $func)
                <tr class="text-c">
                    <td>{{$func['func_id']}}</td>
                    <td>{{$func['func_ename']}}</td>
                    <td>{{$func['func_name']}}</td>
                    <td>{{$func['func_url']}}</td>
                    <td>{{$func['func_img']}}</td>
                    <td>{{$func['func_order']}}</td>
                    <td class="td-status">
                        <span class="label {{$func['func_status'] ? 'label-success': ''}} radius">{{$func['func_status'] ? '显示': '隐藏'}}</span>
                    </td>
                    <td class="td-manage">
                        <a style="text-decoration:none" onClick="admin_start(this,'10001')" href="javascript:;" title="启用"><i class="Hui-iconfont">&#xe615;</i></a>
                        <a title="编辑" href="{{url('admin/sys/func_op?func_id='.$func['func_id'])}}" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                        <a title="删除" href="javascript:;" onclick="admin_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
                    </td>
                </tr>
            @endforeach

        @endforeach
        @foreach($napps as $apps)
        <tr>
            <th scope="col" colspan="9">
                {{$apps->app_name}}(<?=$apps->app_ename?> - <?=$apps->status == 1 ? "可用":"不可用"?>)
            </th>
        </tr>
        <tr style="height:20px">
            <th class="center">编号</th>
            <th class="center">功能代码</th>
            <th class="center">功能名称</th>
            <th class="center">功能网址</th>
            <th class="center">图標16x16</th>
            <th class="center">排序</th>
            <th class="center">状态</th>
            <th class="center">操作 <a href="{{url('admin/sys/func_op?app_id='.$apps->app_id)}}"> 添加功能</a></th>
        </tr>
        <tr><td class="center" colspan="8">暂无功能 </td></tr>
        @endforeach
    </table>
@stop
@section('script')
    <!--请在下方写此页面业务相关的脚本-->
    <script type="text/javascript" src="/client/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/client/admin/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/client/admin/lib/laypage/1.2/laypage.js"></script>
    <script type="text/javascript">
        /*
            参数解释：
            title	标题
            url		请求的url
            id		需要操作的数据id
            w		弹出层宽度（缺省调默认值）
            h		弹出层高度（缺省调默认值）
        */
        /*管理员-增加*/
        function admin_add(title,url,w,h){
            layer_show(title,url,w,h);
        }
        /*管理员-删除*/
        function admin_del(obj,id){
            layer.confirm('确认要删除吗？',function(index){
                //此处请求后台程序，下方是成功后的前台处理……

                $(obj).parents("tr").remove();
                layer.msg('已删除!',{icon:1,time:1000});
            });
        }
        /*管理员-编辑*/
        function admin_edit(title,url,id,w,h){
            layer_show(title,url,w,h);
        }
        /*管理员-停用*/
        function admin_stop(obj,id){
            layer.confirm('确认要停用吗？',function(index){
                //此处请求后台程序，下方是成功后的前台处理……

                $(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_start(this,id)" href="javascript:;" title="启用" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a>');
                $(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">已禁用</span>');
                $(obj).remove();
                layer.msg('已停用!',{icon: 5,time:1000});
            });
        }

        /*管理员-启用*/
        function admin_start(obj,id){
            layer.confirm('确认要启用吗？',function(index){
                //此处请求后台程序，下方是成功后的前台处理……

                $(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_stop(this,id)" href="javascript:;" title="停用" style="text-decoration:none"><i class="Hui-iconfont">&#xe631;</i></a>');
                $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已启用</span>');
                $(obj).remove();
                layer.msg('已启用!', {icon: 6,time:1000});
            });
        }
        /**datadel**/
        function datadel() {
            layer.confirm('确认要启用吗？',function(index){
                if (index) alert('删除成功'); window.location.reload();
            });
        }
    </script>
    <!--/请在上方写此页面业务相关的脚本-->
@stop
