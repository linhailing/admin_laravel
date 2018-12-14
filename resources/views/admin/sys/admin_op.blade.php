<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/14
 * Time: 15:25
 */
?>
@extends('admin/master')
@section('style')
@stop
@section('content')
    <form class="form form-horizontal" id="form-article-add" method="post" action="{{url('admin/sys/app_post')}}">
        @csrf
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>管理员名称：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$admin->username}}" placeholder="请填写管理员名称" name="username">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">真实名称：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$admin->realname}}" placeholder="请填写真实名称" name="realname">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">登录密码：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="password" class="input-text" value="" placeholder="请填写登录密码" name="password">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">角色：</label>
            <div class="formControls col-xs-8 col-sm-9"><span class="select-box">
            <select name="status" class="select">
                <option value="">请选择角色</option>
                @foreach(@$roles as $role)
                    <option value="{{$role->role_id}}">{{$role->role_name}}</option>
                @endforeach
            </select>
            </span>
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
                <button  class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存&nbsp;&nbsp;</button>
                <button  onclick="window.history.go(-1);" class="btn btn-default radius" type="button">&nbsp;&nbsp;返回&nbsp;&nbsp;</button>
            </div>
        </div>
    </form>
@stop
@section('script')
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script>
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
@stop
