<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2018/12/23
 * Time: 12:20 PM
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
            <a href="{{url('admin/book/book_add')}}" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 新增书籍</a>
        </span>
        <span class="r">共有数据：<strong>54</strong> 条</span>
    </div>
    <table class="table table-border table-bordered table-bg">
        <thead>
        <tr>
            <th scope="col" colspan="9">书籍列表</th>
        </tr>
        <tr class="text-c">
            <th>操作</th>
            <th>ID</th>
            <th>书籍名称</th>
            <th>书籍副标题</th>
            <th>作者</th>
            <th>价格</th>
            <th>图片</th>
            <th>isbn</th>
            <th>装帧</th>
        </tr>
        </thead>
        <tbody>
        @if($list)
            @foreach($list as $info)
                <tr class="text-c">
                    <td class="td-manage">
                        <a style="text-decoration:none" onClick="admin_stop(this,'10001')" href="javascript:;" title="停用"><i class="Hui-iconfont">&#xe631;</i></a>
                        <a title="编辑" href="{{url("admin/book/book_add?id=".$info->id)}}" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                        <a title="删除" href="javascript:;" onclick="admin_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
                    <td>{{$info->id}}</td>
                    <td>{{$info->title}}</td>
                    <td>{{$info->subtitle}}</td>
                    <td>{{$info->author}}</td>
                    <td>{{$info->price}}</td>
                    <td><img src="{{APPURLCDN.$info->image}}" width="50px" height="50px"></td>
                    <td>{{$info->isbn}}</td>
                    <td>{{$info->binding}}</td>

                </tr>
            @endforeach
        @else
            <tr>
                <td>暂无数据</td>
            </tr>
        @endif
        </tbody>
    </table>
    <div class="dataTables_paginate pager">
    </div>
@stop
@section('script')
<script type="text/javascript" src="/js/jquery.admin.extend.js"></script>
<script>
    $('.pager').pager({
        align: 'left',
        page: {{@$page}},
        pageSize: {{PAGESIZE}},
        total: {{@$count}},
        showGo: true,
        url: "?page={0}"
    });
</script>
@stop
