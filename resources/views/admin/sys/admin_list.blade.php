<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/14
 * Time: 14:48
 */
?>
@extends('admin/master')
@section('style')
@stop
@section('content')
    <div class="cl pd-5 bg-1 bk-gray">
        <span class="r">共有数据：<strong>54</strong> 条</span>
    </div>
    <table class="table table-border table-bordered table-bg">
        <thead>
        <tr>
            <th scope="col" style="width: 77px;">管理 <a href="{{url('admin/sys/admin_op')}}">新增用户</a></th>
            <th scope="col">账号信息</th>
            <th scope="col">权限</th>
            <th scope="col">真实名称</th>
            <th scope="col">新增时间</th>
            <th scope="col">状态</th>
        </tr>
        </thead>
        @if(count(@$users) > 0)
            @foreach($users as $user)
                <tr>
                    <td>
                        <a title="编辑" href="{{url('admin/sys/admin_op?admin_id='.$user->admin_id)}}" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                        <a title="删除" href="javascript:;" onclick="admin_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
                    </td>
                    <td>{{$user->username}}</td>
                    <td>{{$user->role_funcnames}}</td>
                    <td>{{$user->realname}}</td>
                    <td>{{date('Y-m-d H:i:s',$user->reg_date)}}</td>
                    <td>@if($user->status && $user->status == 1)<span class="label label-success radius">启用</span> @else <span class="label label-danger radius">已停用</span> @endif</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6">s暂无数据~</td>
            </tr>
        @endif
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

