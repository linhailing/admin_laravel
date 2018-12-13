<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/13
 * Time: 13:16
 */
?>

@extends('admin/form')
@section('style')
@stop
@section('content')
    <form class="form form-horizontal" id="form-article-add" method="post" action="{{url('admin/sys/app_post')}}">
        @csrf
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>应用名称：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="请填写应用名称" name="app_name">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">应用代码：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="请填写应用代码" name="app_ename">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">应用icon：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="请填写应用icon" name="app_img">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">排序：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="请填写排序" name="app_order">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">状态：</label>
            <div class="formControls col-xs-8 col-sm-9"><span class="select-box">
            <select name="status" class="select">
                <option value="">请选择状态</option>
                <option value="1">显示</option>
                <option value="0">隐藏</option>
            </select>
            </span>
            </div>
        </div>
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <button  class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存并提交审核</button>
                <button  class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 保存草稿</button>
                <button  class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
            </div>
        </div>
    </form>
@stop
@section('script')
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script>
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
@stop
