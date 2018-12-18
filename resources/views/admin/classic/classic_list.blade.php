<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/12
 * Time: 13:29
 */
?>
@extends('admin/master')
@section('style')
@stop
@section('content')
    <div class="text-c"> 日期范围：
        <input type="text" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'datemax\')||\'%y-%M-%d\'}'})" id="datemin" class="input-text Wdate" style="width:120px;">
        -
        <input type="text" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'datemin\')}',maxDate:'%y-%M-%d'})" id="datemax" class="input-text Wdate" style="width:120px;">
        <input type="text" class="input-text" style="width:250px" placeholder="输入管理员名称" id="" name="">
        <button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜用户</button>
    </div>
    <div class="cl pd-5 bg-1 bk-gray mt-20">
        <span class="l">
            <a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
            <a href="javascript:;" onclick="admin_add('添加管理员','{{url("admin/classic/add")}}','800','500')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加期刊</a>
        </span>
        <span class="r">共有数据：<strong>54</strong> 条</span>
    </div>
    <table class="table table-border table-bordered table-bg">
        <thead>
        <tr>
            <th scope="col" colspan="9">员工列表</th>
        </tr>
        <tr class="text-c">
            <th>操作</th>
            <th>ID</th>
            <th>期刊ID</th>
            <th>期刊标题</th>
            <th>发布时间</th>
            <th>类型</th>
            <th>图片</th>
            <th>内容</th>
            <th>music url</th>
        </tr>
        </thead>
        <tbody>
        @if($list)
         @foreach($list as $info)
        <tr class="text-c">
            <td class="td-manage">
                <a style="text-decoration:none" onClick="admin_stop(this,'10001')" href="javascript:;" title="停用"><i class="Hui-iconfont">&#xe631;</i></a>
                <a title="编辑" href="javascript:;" onclick="admin_edit('管理员编辑','{{url("admin/classic/add?id=".$info->id)}}','1','800','500')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                <a title="删除" href="javascript:;" onclick="admin_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
            <td>{{$info->id}}</td>
            <td>{{$info->index}}</td>
            <td>{{$info->title}}</td>
            <td>{{$info->pubdate}}</td>
            <td>@if($info->type == 100)电影 @elseif($info->type == 200)音乐 @else 句子 @endif</td>
            <td><img src="{{APPURLCDN.$info->image}}" width="50px" height="50px"></td>
            <td>{{$info->content}}</td>
            <td>{{$info->src}}</td>

        </tr>
        @endforeach
        @else
            <tr>
                <td>暂无数据</td>
            </tr>
        @endif
        </tbody>
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

