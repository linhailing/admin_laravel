<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/13
 * Time: 12:57
 */
?>
@extends('admin/master')
@section('style')
@stop
@section('content')
    <form class="form form-horizontal" id="form-article-add" method="post" action="{{url('admin/sys/func_post?func_id='.$func_id)}}">
        @csrf
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>应用名称：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <span class="select-box">
                    <select name="app_id" class="select">
                        <option value="">请选择应用名称</option>
                        @foreach($apps as $app)
                        <option value="{{$app->app_id}}" @if((!empty($app_id) && $app_id == $app->app_id) || (!empty(@$func->app_id) && @$func->app_id == $app->app_id)) selected @endif>{{$app->app_name}}({{$app->app_ename}})</option>
                        @endforeach
                    </select>
                </span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">功能名称：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$func->func_name}}" placeholder="请填写应用代码" name="func_name">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">功能代码：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$func->func_ename}}" placeholder="请填写应用代码" name="func_ename">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">网址：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$func->func_url}}" placeholder="请填写应用icon" name="func_url">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">功能icon：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$func->func_img}}" placeholder="请填写应用icon" name="func_img">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">排序：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$func->func_order}}" placeholder="请填写排序" name="func_order">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">状态：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <span class="select-box">
                    <select name="status" class="select">
                        <option value="">请选择状态</option>
                        <option value="1" @if(@$func->status == 1) selected @endif>显示</option>
                        <option value="0" @if(@$func->status == 0) selected @endif>隐藏</option>
                    </select>
                </span>
            </div>
        </div>
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <button onClick="article_save_submit();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存</button>
                <button onClick="window.history.go(-1);" class="btn btn-default radius" type="button">返回</button>
            </div>
        </div>
    </form>
@stop
@section('script')
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script>
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
@stop

